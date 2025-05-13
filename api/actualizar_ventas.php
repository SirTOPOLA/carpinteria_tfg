<?php
require '../config/conexion.php';
header('Content-Type: application/json');

try {
    if (!isset($_POST['venta_id'], $_POST['cliente_id'], $_POST['metodo_pago'], $_POST['tipo'])) {
        throw new Exception('Faltan datos requeridos');
    }

    
    $pdo->beginTransaction();

    $venta_id = $_POST['venta_id'];
    $cliente_id = $_POST['cliente_id'];
    $metodo_pago = trim($_POST['metodo_pago']);
    $total = $_POST['total'];

    // Actualizar venta
    $stmt = $pdo->prepare("UPDATE ventas SET cliente_id = ?, metodo_pago = ?, total = ? WHERE id = ?");
    $stmt->execute([$cliente_id, $metodo_pago, $total, $venta_id]);

    // Eliminar detalles anteriores
    $pdo->prepare("DELETE FROM detalles_venta WHERE venta_id = ?")->execute([$venta_id]);

    // Insertar nuevos detalles
    $tipos = $_POST['tipo'];
    $cantidades = $_POST['cantidad'];
    $precios = $_POST['precio_unitario'];

    foreach ($tipos as $i => $tipo) {
        $item_id = $_POST[$tipo . '_id'][$i];
        $cantidad = $cantidades[$i];
        $precio_unitario = $precios[$i];

        $stmt = $pdo->prepare("INSERT INTO detalles_venta (venta_id, tipo, item_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$venta_id, $tipo, $item_id, $cantidad, $precio_unitario]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Venta actualizada correctamente.']);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
