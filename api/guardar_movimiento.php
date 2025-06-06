<?php
require '../config/conexion.php';
header('Content-Type: application/json');

try {
    $materialId = isset($_POST['material_id']) ? intval($_POST['material_id']) : 0;
    $produccionId = isset($_POST['produccion_id']) ? intval($_POST['produccion_id']) : 0;
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;
    $motivo = isset($_POST['motivo']) ? trim($_POST['motivo']) : '';
    $tipoMovimiento = isset($_POST['tipo']) && in_array($_POST['tipo'], ['salida', 'entrada']) ? $_POST['tipo'] : 'salida';

    if ($materialId <= 0 || $produccionId <= 0 || $cantidad <= 0 || empty($motivo)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos o inválidos']);
        exit;
    }

    // Verificar existencia del material y stock
    $stmt = $pdo->prepare("SELECT stock_actual FROM materiales WHERE id = ?");
    $stmt->execute([$materialId]);
    $material = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$material) {
        echo json_encode(['success' => false, 'message' => 'Material no encontrado']);
        exit;
    }

    // Obtener el pedido relacionado a la producción
    $stmt = $pdo->prepare("SELECT solicitud_id FROM producciones WHERE id = ?");
    $stmt->execute([$produccionId]);
    $produccion = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$produccion) {
        echo json_encode(['success' => false, 'message' => 'Producción no encontrada']);
        exit;
    }
    $pedidoId = $produccion['solicitud_id'];

    // Obtener cantidad solicitada para ese material en el pedido
    $stmt = $pdo->prepare("SELECT cantidad FROM detalles_pedido_material WHERE pedido_id = ? AND material_id = ?");
    $stmt->execute([$pedidoId, $materialId]);
    $detalle = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$detalle) {
        echo json_encode(['success' => false, 'message' => 'El material no está incluido en el pedido']);
        exit;
    }
    $cantidadSolicitada = $detalle['cantidad'];

    // Calcular total de movimientos anteriores (salidas y entradas)
    $stmt = $pdo->prepare("SELECT 
        SUM(CASE WHEN tipo_movimiento = 'salida' THEN cantidad ELSE 0 END) AS total_salida,
        SUM(CASE WHEN tipo_movimiento = 'entrada' THEN cantidad ELSE 0 END) AS total_entrada
        FROM movimientos_material
        WHERE produccion_id = ? AND material_id = ?");
    $stmt->execute([$produccionId, $materialId]);
    $movimientos = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalSalidas = intval($movimientos['total_salida'] ?? 0);
    $totalEntradas = intval($movimientos['total_entrada'] ?? 0);

    // Validaciones según el tipo de movimiento
    if ($tipoMovimiento === 'salida') {
        if ($material['stock_actual'] < $cantidad) {
            echo json_encode(['success' => false, 'message' => 'Stock insuficiente para la salida']);
            exit;
        }
        if (($totalSalidas + $cantidad) > $cantidadSolicitada) {
            echo json_encode(['success' => false, 'message' => 'La cantidad total de salida supera la solicitada']);
            exit;
        }
    } else { // entrada
        if ($totalSalidas === 0) {
            echo json_encode(['success' => false, 'message' => 'No puede hacer un retorno sin haber hecho una salida previa']);
            exit;
        }
    }

    // Iniciar transacción
    $pdo->beginTransaction();

    // Insertar movimiento
    $stmt = $pdo->prepare("INSERT INTO movimientos_material 
        (material_id, tipo_movimiento, cantidad, motivo, produccion_id)
        VALUES (:material_id, :tipo, :cantidad, :motivo, :produccion_id)");
    $stmt->execute([
        ':material_id' => $materialId,
        ':tipo' => $tipoMovimiento,
        ':cantidad' => $cantidad,
        ':motivo' => $motivo,
        ':produccion_id' => $produccionId
    ]);

    // Actualizar stock según tipo de movimiento
    if ($tipoMovimiento === 'salida') {
        $stmt = $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual - :cantidad WHERE id = :id");
    } else {
        $stmt = $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual + :cantidad WHERE id = :id");
    }
    $stmt->execute([':cantidad' => $cantidad, ':id' => $materialId]);

    // Actualizar estado de la producción (solo si es salida)
    if ($tipoMovimiento === 'salida') {
        $stmt = $pdo->prepare("SELECT id FROM estados WHERE nombre = 'en_proceso' AND entidad = 'produccion' LIMIT 1");
        $stmt->execute();
        $estado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($estado) {
            $stmt = $pdo->prepare("UPDATE producciones SET estado_id = :estado_id WHERE id = :produccion_id");
            $stmt->execute([
                ':estado_id' => $estado['id'],
                ':produccion_id' => $produccionId
            ]);
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error al registrar movimiento: ' . $e->getMessage()]);
}
