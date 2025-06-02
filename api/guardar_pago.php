<?php
require_once '../config/conexion.php';
header("Content-Type: application/json");

$response = ['success' => false, 'message' => ''];

try {
    $factura_id = $_POST['factura_id'];
    $monto_pagado = floatval($_POST['monto_pagado']);
    $metodo = $_POST['metodo_pago'];
    $observaciones = $_POST['observaciones'] ?? '';

    if ($monto_pagado <= 0) {
        throw new Exception("El monto debe ser mayor a cero.");
    }

    // Obtener saldo pendiente actual
    $stmt = $pdo->prepare("SELECT saldo_pendiente FROM facturas WHERE id = ?");
    $stmt->execute([$factura_id]);
    $factura = $stmt->fetch();

    if (!$factura) throw new Exception("Factura no encontrada.");

    $nuevoSaldo = $factura['saldo_pendiente'] - $monto_pagado;
    if ($nuevoSaldo < 0) $nuevoSaldo = 0;

    // Insertar el nuevo pago
    $pdo->prepare("INSERT INTO pagos (factura_id, monto_pagado, fecha_pago, metodo_pago, observaciones) 
                   VALUES (?, ?, NOW(), ?, ?)")
        ->execute([$factura_id, $monto_pagado, $metodo, $observaciones]);

    // Obtener el ID del estado correspondiente
    $estado_nombre = $nuevoSaldo <= 0 ? 'Pagada' : 'Pendiente';

    $stmtEstado = $pdo->prepare("SELECT id FROM estados WHERE nombre = ? AND entidad = 'factura'");
    $stmtEstado->execute([$estado_nombre]);
    $estado = $stmtEstado->fetch();

    if (!$estado) {
        throw new Exception("Estado '{$estado_nombre}' no registrado en la tabla estados.");
    }

    // Actualizar el saldo pendiente y el estado_id
    $pdo->prepare("UPDATE facturas SET saldo_pendiente = ?, estado_id = ? WHERE id = ?")
        ->execute([$nuevoSaldo, $estado['id'], $factura_id]);

    $response['success'] = true;

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
