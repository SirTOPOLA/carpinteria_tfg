<?php
require '../config/conexion.php';
header('Content-Type: application/json; charset=utf-8');

$idProduccion = isset($_POST['id']) ? (int) $_POST['id'] : null;
$nuevoEstado = isset($_POST['estado']) ? trim($_POST['estado']) : null;
$fotoProducto = $_FILES['foto'] ?? null;

if (!$idProduccion || $nuevoEstado !== 'finalizado') {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos o estado no permitido.']);
    exit;
}

$pdo->beginTransaction();

try {
    // 1. Verificar porcentaje total de avances
    $stmt = $pdo->prepare("SELECT SUM(porcentaje) AS total FROM avances_produccion WHERE produccion_id = ?");
    $stmt->execute([$idProduccion]);
    $total = (int) $stmt->fetchColumn();

    // 2. Si < 100, completar
    if ($total < 100) {
        $faltante = 100 - $total;
        $stmt = $pdo->prepare("
            INSERT INTO avances_produccion (produccion_id, descripcion, imagen, porcentaje, fecha)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $idProduccion,
            'Avance automático para completar producción.',
            null,
            $faltante
        ]);
    }

    // 3. Finalizar producción
    $stmt = $pdo->prepare("
        UPDATE producciones 
        SET estado_id = (
            SELECT id FROM estados WHERE nombre = 'finalizado' AND entidad = 'produccion'
        ), fecha_fin = CURDATE()
        WHERE id = ?
    ");
    $stmt->execute([$idProduccion]);

    // 4. Obtener el pedido relacionado
    $stmt = $pdo->prepare("
        SELECT p.id, p.proyecto 
        FROM pedidos p 
        JOIN producciones pr ON pr.solicitud_id = p.id 
        WHERE pr.id = ?
    ");
    $stmt->execute([$idProduccion]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
    $idPedido = $pedido['id'] ?? null;
    $nombreProyecto = trim($pedido['proyecto'] ?? '');

    if ($idPedido) {
        $stmt = $pdo->prepare("
            UPDATE pedidos 
            SET estado_id = (
                SELECT id FROM estados WHERE nombre = 'finalizado' AND entidad = 'pedido'
            )
            WHERE id = ?
        ");
        $stmt->execute([$idPedido]);
    }

    // 5. Verificar si el proyecto existe como nombre exacto de producto
    $stmt = $pdo->prepare("SELECT id FROM productos WHERE BINARY nombre = ?");
    $stmt->execute([$nombreProyecto]);
    $productoExistente = $stmt->fetchColumn();

    if (!$productoExistente) {
        // Insertar nuevo producto
        $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, stock ) VALUES (?, ?, 0)");
        $stmt->execute([$nombreProyecto, 'Producto generado automáticamente desde producción']);
        $productoExistente = $pdo->lastInsertId();

        // Insertar detalle_produccion asociado (si no existe ya)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM detalles_produccion WHERE produccion_id = ? AND producto_id = ?");
        $stmt->execute([$idProduccion, $productoExistente]);
        $existeDetalle = $stmt->fetchColumn();

        if (!$existeDetalle) {
            $stmt = $pdo->prepare("
                INSERT INTO detalles_produccion (produccion_id, producto_id, descripcion, cantidad)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $idProduccion,
                $productoExistente,
                'Asociación automática desde nombre de proyecto',
                1
            ]);
        }
    }

    // 6. Generar productos fabricados según detalles
    $stmt = $pdo->prepare("
        SELECT producto_id, cantidad 
        FROM detalles_produccion 
        WHERE produccion_id = ?
    ");
    $stmt->execute([$idProduccion]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $resumen = [];

    foreach ($productos as $prod) {
        $productoId = (int)$prod['producto_id'];
        $cantidad = (int)$prod['cantidad'];

        if ($productoId <= 0 || $cantidad <= 0) {
            throw new Exception("Datos inválidos para producto_id ($productoId) o cantidad ($cantidad)");
        }

        // Actualizar stock
        $stmtUpdate = $pdo->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
        $stmtUpdate->execute([$cantidad, $productoId]);

        $resumen[] = [
            'producto_id' => $productoId,
            'cantidad_fabricada' => $cantidad
        ];
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Producción finalizada correctamente.',
        'resumen' => $resumen
    ]);
}  
catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'message' => 'Error al finalizar la producción.',
        'error' => $e->getMessage()
    ]);
}
