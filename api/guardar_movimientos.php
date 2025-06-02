<?php

require_once '../config/conexion.php'; // tu archivo de conexion

// Respuesta JSON
header('Content-Type: application/json');
$response = ['status' => false, 'message' => 'Error inesperado'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => false, 'message' => 'Método no permitido']);
    exit;
}

// Validaciones básicas iniciales
$produccion_id = isset($_POST['produccion_id']) ? (int) $_POST['produccion_id'] : 0;
$observaciones = trim($_POST['observaciones'] ?? '');
$materiales = $_POST['materiales'] ?? [];

if (!$produccion_id || empty($materiales)) {
    echo json_encode(['status' => false, 'message' => 'Producción o materiales no válidos.']);
    exit;
}

try {
    $pdo->beginTransaction();

    foreach ($materiales as $mat) {
        $material_id = (int) ($mat['material_id'] ?? 0);
        $cantidad = (int) ($mat['cantidad'] ?? 0);

        if ($material_id <= 0 || $cantidad <= 0) {
            throw new Exception('Material o cantidad inválida. '.$material_id);
        }

        // Obtener la cantidad solicitada en detalles_solicitud_material
        $stmt = $pdo->prepare("SELECT dsm.cantidad AS cantidad_solicitada
                               FROM detalles_solicitud_material dsm
                               INNER JOIN solicitudes_proyecto sp ON dsm.solicitud_id = sp.id
                               INNER JOIN producciones p ON p.proyecto_id = sp.proyecto_id
                               WHERE p.id = ? AND dsm.material_id = ?");
        $stmt->execute([$produccion_id, $material_id]);
        $detalle = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$detalle) {
            throw new Exception("El material no está asociado a la producción.");
        }

        $cantidad_solicitada = (int) $detalle['cantidad_solicitada'];

        // Obtener total ya movido para esta producción y material
        $stmt = $pdo->prepare("SELECT COALESCE(SUM(cantidad), 0) AS cantidad_movida
                               FROM movimientos_material
                               WHERE material_id = ? AND produccion_id = ? AND tipo_movimiento = 'salida'");
        $stmt->execute([$material_id, $produccion_id]);
        $movido = $stmt->fetch(PDO::FETCH_ASSOC);
        $cantidad_movida = (int) $movido['cantidad_movida'];

        $cantidad_disponible_produccion = $cantidad_solicitada - $cantidad_movida;

        if ($cantidad > $cantidad_disponible_produccion) {
            throw new Exception("Cantidad excede lo solicitado para el material ID $material_id. Quedan $cantidad_disponible_produccion.");
        }

        // Verificar stock disponible actual en materiales
        $stmt = $pdo->prepare("SELECT stock_actual FROM materiales WHERE id = ?");
        $stmt->execute([$material_id]);
        $mat_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$mat_data) {
            throw new Exception("Material no encontrado en inventario.");
        }

        if ((int)$mat_data['stock_actual'] < $cantidad) {
            throw new Exception("Stock insuficiente para material ID $material_id. Disponible: {$mat_data['stock_actual']}.");
        }

        // Insertar movimiento
        $stmt = $pdo->prepare("INSERT INTO movimientos_material (material_id, tipo_movimiento, cantidad, motivo, produccion_id)
                               VALUES (?, 'salida', ?, ?, ?)");
        $stmt->execute([$material_id, $cantidad, $observaciones, $produccion_id]);

        // Descontar del stock actual
        $stmt = $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual - ? WHERE id = ?");
        $stmt->execute([$cantidad, $material_id]);
    }

    $pdo->commit();
    $response = ['status' => true, 'message' => 'Movimiento registrado con éxito.'];
} catch (Exception $e) {
    $pdo->rollBack();
    $response = ['status' => false, 'message' => $e->getMessage()];
}

echo json_encode($response);
