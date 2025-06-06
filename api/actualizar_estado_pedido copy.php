<?php

require '../config/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idPedido = intval($_POST['id']);
    $estadoTexto = trim($_POST['estado']);

    try {
        $pdo->beginTransaction();

        // Buscar el ID del estado "aprobado" para pedidos
        $stmtEstado = $pdo->prepare("SELECT id FROM estados WHERE nombre = 'aprobado' AND entidad = 'pedido' LIMIT 1");
        $stmtEstado->execute();
        $estado = $stmtEstado->fetch(PDO::FETCH_ASSOC);

        if (!$estado) {
            throw new Exception("Estado 'aprobado' no encontrado.");
        }

        // Obtener información del pedido
        $stmt = $pdo->prepare("
            SELECT p.*, e.nombre AS estado 
            FROM pedidos p 
            LEFT JOIN estados e ON p.estado_id = e.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$idPedido]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            throw new Exception("Pedido no encontrado.");
        }

        if ($pedido['estado'] !== 'cotizado') {
            throw new Exception("El pedido no está en estado 'cotizado' o ya fue procesado.");
        }

        // Actualizar estado del pedido a "aprobado"
        $stmt = $pdo->prepare("UPDATE pedidos SET estado_id = ? WHERE id = ?");
        $stmt->execute([$estado['id'], $idPedido]);

        // Registrar nuevo producto con stock inicial 0
        $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio_unitario, stock)
                               VALUES (?, ?, ?, 0)");
        $stmt->execute([
            $pedido['proyecto'], 
            $pedido['descripcion'],  // corregido el nombre del campo
            $pedido['estimacion_total']
        ]);
        $producto_id = $pdo->lastInsertId();

        // Registrar venta
        $stmt = $pdo->prepare("INSERT INTO ventas (cliente_id, total, fecha, metodo_pago) VALUES (?, ?, NOW(), ?)");
        $stmt->execute([$pedido['cliente_id'], $pedido['estimacion_total'], 'efectivo']);
        $idVenta = $pdo->lastInsertId();

        // Insertar detalles de la venta
        if (empty($pedido['servicio_id'])) {  // corregido el nombre del campo
            // Solo producto
            $stmt = $pdo->prepare("INSERT INTO detalles_venta 
                (venta_id, tipo, producto_id, servicio_id, cantidad, precio_unitario, subtotal)
                VALUES (?, 'producto', ?, NULL, 1, ?,?)");
            $stmt->execute([$idVenta, $producto_id, $pedido['estimacion_total'],$pedido['estimacion_total']]);
        } else {
            // Producto
            $stmt = $pdo->prepare("INSERT INTO detalles_venta 
                (venta_id, tipo, producto_id, servicio_id, cantidad, precio_unitario,subtotal)
                VALUES (?, 'producto', ?, ?, 1,?, ?)");
            $stmt->execute([$idVenta, $producto_id, $pedido['servicio_id'], $pedido['estimacion_total']]);

            // Servicio
            $stmtServicio = $pdo->prepare("SELECT precio_base FROM servicios WHERE id = ?");
            $stmtServicio->execute([$pedido['servicio_id']]);
            $servicio = $stmtServicio->fetch(PDO::FETCH_ASSOC);

            if (!$servicio) {
                throw new Exception("Servicio no encontrado.");
            }

            $stmt = $pdo->prepare("INSERT INTO detalles_venta 
                (venta_id, tipo, producto_id, servicio_id, cantidad, precio_unitario)
                VALUES (?, 'servicio', NULL, ?, 1, ?)");
            $stmt->execute([$idVenta, $pedido['servicio_id'], $servicio['precio_base']]);
        }

        $pdo->commit();

        echo json_encode(['success' => true, 'venta_id' => $idVenta]);

    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
