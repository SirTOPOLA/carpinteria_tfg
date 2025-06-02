<?php
require_once '../config/conexion.php';
header("Content-Type: application/json");
 
if (!isset($_POST['venta_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Falta el ID de la venta']);
    exit;
}

$venta_id = intval($_POST['venta_id']);

try {
     

    // 1. Obtener datos de la venta
    $stmt = $pdo->prepare("SELECT total, metodo_pago FROM ventas WHERE id = ?");
    $stmt->execute([$venta_id]);
    $venta = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$venta) {
        throw new Exception("Venta no encontrada");
    }

    $total = $venta['total'];
    $metodo = strtolower($venta['metodo_pago']);
    $estado_id = ($metodo === 'contado') ? 2 : 1; // 2 = pagada, 1 = pendiente
    $saldo = ($estado_id === 2) ? 0.00 : $total;

    // 2. Insertar la factura
    $stmt = $pdo->prepare("INSERT INTO facturas (venta_id, fecha_emision, monto_total, saldo_pendiente, estado_id)
                           VALUES (?, CURDATE(), ?, ?, ?)");
    $stmt->execute([$venta_id, $total, $saldo, $estado_id]);

    // 3. Si es contado, registrar pago automáticamente
    if ($estado_id === 2) {
        $factura_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("INSERT INTO pagos (factura_id, monto_pagado, fecha_pago, metodo_pago, observaciones)
                               VALUES (?, ?, CURDATE(), ?, 'Pago al contado')");
        $stmt->execute([$factura_id, $total, $venta['metodo_pago']]);
    }

    echo json_encode(['success' => true, 'mensaje' => 'Factura emitida correctamente.']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al emitir factura: ' . $e->getMessage()]);
}
?>
