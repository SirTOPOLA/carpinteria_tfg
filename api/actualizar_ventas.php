<?php
require '../config/conexion.php';
header('Content-Type: application/json');

try {
    if (!isset($_POST['venta_id'], $_POST['cliente_id'],  $_POST['tipo'])) {
        throw new Exception('Faltan datos requeridos');
    }

    $pdo->beginTransaction();

    $venta_id = intval($_POST['venta_id']);
    $cliente_id = intval($_POST['cliente_id']);
    $metodo_pago = trim($_POST['metodo_pago']);
    $total = floatval($_POST['total']);

    // Restaurar stock anterior antes de eliminar detalles
    $stmtDetallesPrevios = $pdo->prepare("SELECT tipo, producto_id, cantidad FROM detalles_venta WHERE venta_id = ?");
    $stmtDetallesPrevios->execute([$venta_id]);

    while ($detalle = $stmtDetallesPrevios->fetch(PDO::FETCH_ASSOC)) {
        if ($detalle['tipo'] === 'producto' && !empty($detalle['producto_id'])) {
            // Sumar nuevamente la cantidad al stock
            $stmtRestaurar = $pdo->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
            $stmtRestaurar->execute([$detalle['cantidad'], $detalle['producto_id']]);
        }
    }

    // Eliminar detalles anteriores
    $pdo->prepare("DELETE FROM detalles_venta WHERE venta_id = ?")->execute([$venta_id]);

    // Actualizar venta
    $stmt = $pdo->prepare("UPDATE ventas SET cliente_id = ?, metodo_pago = ?, total = ? WHERE id = ?");
    $stmt->execute([$cliente_id, $metodo_pago, $total, $venta_id]);

    // Insertar nuevos detalles y descontar stock si corresponde
    $tipos = $_POST['tipo'];
    $cantidades = $_POST['cantidad'];
    $precios = $_POST['precio_unitario'];
    $descuentos = $_POST['descuento'] ?? [];

    foreach ($tipos as $i => $tipo) {
        $tipo = trim($tipo);
        $item_id = $_POST[$tipo . '_id'][$i];
        $cantidad = intval($cantidades[$i]);
        $precio_unitario = floatval($precios[$i]);
        $descuento = isset($descuentos[$i]) ? floatval($descuentos[$i]) : 0;

        $producto_id = ($tipo === 'producto') ? $item_id : null;
        $servicio_id = ($tipo === 'servicio') ? $item_id : null;

        $subtotal = $precio_unitario * $cantidad;
        if ($descuento > 0) {
            $subtotal -= ($subtotal * $descuento / 100);
        }

        $stmt = $pdo->prepare("
            INSERT INTO detalles_venta (
                venta_id, tipo, producto_id, servicio_id, cantidad, precio_unitario, descuento, subtotal
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $venta_id,
            $tipo,
            $producto_id,
            $servicio_id,
            $cantidad,
            $precio_unitario,
            $descuento,
            $subtotal
        ]);

        // Descontar stock si es producto
        if ($tipo === 'producto') {
            // Verificar stock actual
            $stmtStock = $pdo->prepare("SELECT stock FROM productos WHERE id = ?");
            $stmtStock->execute([$producto_id]);
            $producto = $stmtStock->fetch(PDO::FETCH_ASSOC);

            if (!$producto) {
                throw new Exception("Producto con ID $producto_id no encontrado.");
            }

            if ($producto['stock'] < $cantidad) {
                throw new Exception("Stock insuficiente para el producto con ID $producto_id.");
            }

            $stmtDescontar = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
            $stmtDescontar->execute([$cantidad, $producto_id]);
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Venta actualizada correctamente.']);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
