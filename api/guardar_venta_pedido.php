<?php
require_once "../config/conexion.php"; // Clase de conexión

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Acceso no permitido.']);
    exit;
}

if (empty($_POST['pedido_id']) || !is_numeric($_POST['pedido_id'])) {
    echo json_encode(['error' => 'ID de pedido inválido.']);
    exit;
}

$pedido_id = intval($_POST['pedido_id']);
$descuento = isset($_POST['descuento_pedido']) ? floatval($_POST['descuento_pedido']) : 0.00;

try {
    // Obtener datos del pedido y servicio
    $stmt = $pdo->prepare("
        SELECT p.*, s.nombre AS servicio_nombre, s.precio_base AS servicio_precio
        FROM pedidos p
        LEFT JOIN servicios s ON s.id = p.servicio_id
        WHERE p.id = ?
    ");
    $stmt->execute([$pedido_id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        echo json_encode(['error' => 'Pedido no encontrado.']);
        exit;
    }

    if ((int)$pedido['estado_id'] === 3) {
        echo json_encode(['error' => 'El pedido ya fue entregado.']);
        exit;
    }

    $pdo->beginTransaction();

    // Insertar en ventas
    $stmtVenta = $pdo->prepare("
        INSERT INTO ventas (cliente_id, nombre_cliente, dni_cliente, direccion_cliente, total, metodo_pago)
        VALUES (?, NULL, NULL, NULL, ?, ?)
    ");
    $stmtVenta->execute([
        $pedido['cliente_id'],
        $pedido['estimacion_total'],
        'efectivo'
    ]);
    $venta_id = $pdo->lastInsertId();

    // Calcular subtotal
    $subtotal =  $pedido['estimacion_total']  - $descuento;

    // Insertar detalle de venta con tipo 'pedido'
    $stmtDetallePedido = $pdo->prepare("
        INSERT INTO detalles_venta (venta_id, tipo, servicio_id, cantidad, precio_unitario, descuento, subtotal)
        VALUES (?, 'pedido', ?, ?, ?, ?, ?)
    ");
    $stmtDetallePedido->execute([
        $venta_id,
        $pedido['servicio_id'],     // Puede ser null si no tiene servicio asociado
        $pedido['piezas'],
        $pedido['estimacion_total'],
        $descuento,
        $subtotal
    ]);

    // Marcar el pedido como entregado
    $stmtUpdate = $pdo->prepare("
        UPDATE pedidos 
        SET estado_id = (SELECT id FROM estados WHERE nombre = 'entregado' AND entidad = 'pedido') 
        WHERE id = ?
    ");
    $stmtUpdate->execute([$pedido_id]);

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Venta registrada correctamente.', 'venta_id' => $venta_id]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['error' => 'Error al registrar la venta: ' . $e->getMessage()]);
}
?>
