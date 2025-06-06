<?php
require_once '../config/conexion.php';
header("Content-Type: application/json");

$response = ['success' => false, 'message' => ''];

try {
    $factura_id = $_POST['factura_id'] ?? null;
    $monto_pagado = floatval($_POST['monto_pagado'] ?? 0);
    $metodo = $_POST['metodo_pago'] ?? '';
    $observaciones = $_POST['observaciones'] ?? '';

    if (!$factura_id) {
        throw new Exception("Factura ID es obligatorio.");
    }
    if ($monto_pagado <= 0) {
        throw new Exception("El monto debe ser mayor a cero.");
    }

    // Obtener saldo pendiente y venta_id desde factura
    $stmt = $pdo->prepare("SELECT saldo_pendiente, venta_id FROM facturas WHERE id = ?");
    $stmt->execute([$factura_id]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        throw new Exception("Factura no encontrada.");
    }

    $nuevoSaldo = $factura['saldo_pendiente'] - $monto_pagado;
    if ($nuevoSaldo < 0)
        $nuevoSaldo = 0;

    // Obtener cliente_id desde venta
    $stmtVenta = $pdo->prepare("SELECT cliente_id FROM ventas WHERE id = ?");
    $stmtVenta->execute([$factura['venta_id']]);
    $venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

    if (!$venta) {
        throw new Exception("Venta no encontrada.");
    }
    $cliente_id = $venta['cliente_id'];

    // Insertar el pago
    $pdo->prepare("INSERT INTO pagos (factura_id, monto_pagado, fecha_pago, metodo_pago, observaciones) 
                   VALUES (?, ?, NOW(), ?, ?)")
        ->execute([$factura_id, $monto_pagado, $metodo, $observaciones]);

    // Obtener el ID del estado para la factura
    $estado_nombre = $nuevoSaldo <= 0 ? 'Pagada' : 'Pendiente';
    $stmtEstado = $pdo->prepare("SELECT id FROM estados WHERE nombre = ? AND entidad = 'factura'");
    $stmtEstado->execute([$estado_nombre]);
    $estado = $stmtEstado->fetch(PDO::FETCH_ASSOC);

    if (!$estado) {
        throw new Exception("Estado '{$estado_nombre}' no registrado en la tabla estados.");
    }

    // Actualizar saldo pendiente y estado en factura
    $pdo->prepare("UPDATE facturas SET saldo_pendiente = ?, estado_id = ? WHERE id = ?")
        ->execute([$nuevoSaldo, $estado['id'], $factura_id]);

    // Verificar si cliente_id está en pedidos
    $stmtPedido = $pdo->prepare("SELECT id FROM pedidos WHERE cliente_id = ?");
    $stmtPedido->execute([$cliente_id]);
    $pedido = $stmtPedido->fetch(PDO::FETCH_ASSOC);

    // Verificar si cliente_id está en pedidos y si el saldo está totalmente pagado
    if ($pedido && $nuevoSaldo == 0) {
        // Cliente tiene pedido(s) y pago está completo, actualizar estado a 'entregado'

        // Obtener id de estado 'entregado' para entidad pedido
        $stmtEstadoPedido = $pdo->prepare("SELECT id FROM estados WHERE nombre = 'entregado' AND entidad = 'pedido'");
        $stmtEstadoPedido->execute();
        $estadoPedido = $stmtEstadoPedido->fetch(PDO::FETCH_ASSOC);

        if (!$estadoPedido) {
            throw new Exception("Estado 'entregado' no registrado en la tabla estados.");
        }

        // Actualizar todos los pedidos de ese cliente a estado 'entregado'
        $pdo->prepare("UPDATE pedidos SET estado_id = ? WHERE cliente_id = ?")
            ->execute([$estadoPedido['id'], $cliente_id]);
    }

    $response['success'] = true;
    $response['message'] = "Pago registrado correctamente.";

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
