<?php

require_once '../config/conexion.php'; // $pdo debe estar definido aquí

header("Content-Type: application/json");


try {
    // Validar existencia de datos mínimos

    $clienteId = !empty($_POST['cliente_id']) ? intval($_POST['cliente_id']) : null;
    $nombreCliente = $_POST['nombre_cliente'] ?? null;
    $dniCliente = $_POST['dni_cliente'] ?? null;
    $direccionCliente = $_POST['direccion_cliente'] ?? null;

    if (is_null($clienteId) && empty($nombreCliente)) {
        echo json_encode(['success' => false, 'message' => 'Debe seleccionar un cliente o ingresar su nombre.']);
        exit;
    }

    if (empty($_POST['tipo']) || !is_array($_POST['tipo'])) {
        throw new Exception("No hay detalles de venta.");
    }

    $metodoPago = $_POST['metodo_pago'] ?? 'efectivo';
    $totalVenta = floatval($_POST['total'] ?? 0);


    // 1. VALIDACIÓN PREVIA de productos y stock
    foreach ($_POST['tipo'] as $i => $tipo) {
        $tipo = trim($tipo);
        $itemId = isset($_POST['item_id'][$i]) ? intval($_POST['item_id'][$i]) : null;
        $cantidad = isset($_POST['cantidad'][$i]) ? intval($_POST['cantidad'][$i]) : 1;

        if (!in_array($tipo, ['producto', 'servicio']) || !$itemId) {
            throw new Exception("Datos de producto o servicio inválidos en la posición $i.");
        }

        if ($tipo === 'producto') {
            $stmtStock = $pdo->prepare("SELECT nombre, stock FROM productos WHERE id = ?");
            $stmtStock->execute([$itemId]);
            $producto = $stmtStock->fetch(PDO::FETCH_ASSOC);

            if (!$producto) {
                throw new Exception("Producto con ID $itemId no encontrado.");
            }

            if ($producto['stock'] < $cantidad) {
                throw new Exception("Stock insuficiente para el producto:  {$producto['nombre']}. con ID $itemId. Disponible: {$producto['stock']}, solicitado: $cantidad.");
            }
        }
    }



    // Iniciar transacción
    $pdo->beginTransaction();

    // Insertar venta con datos completos
    $stmtVenta = $pdo->prepare("
        INSERT INTO ventas (
            cliente_id, nombre_cliente, dni_cliente, direccion_cliente,
            total, metodo_pago, fecha
        )
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmtVenta->execute([
        $clienteId,
        $nombreCliente,
        $dniCliente,
        $direccionCliente,
        $totalVenta,
        $metodoPago
    ]);

    $ventaId = $pdo->lastInsertId();

    // Insertar detalles
    $stmtDetalle = $pdo->prepare("
        INSERT INTO detalles_venta (
            venta_id, tipo, producto_id, servicio_id, cantidad,
            precio_unitario, descuento, subtotal
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($_POST['tipo'] as $i => $tipo) {
        $tipo = trim($tipo);
        $itemId = isset($_POST['item_id'][$i]) ? intval($_POST['item_id'][$i]) : null;
        $cantidad = isset($_POST['cantidad'][$i]) ? intval($_POST['cantidad'][$i]) : 1;
        $precio = isset($_POST['precio_unitario'][$i]) ? floatval(str_replace(',', '.', $_POST['precio_unitario'][$i])) : 0;
        $descuento = isset($_POST['descuento'][$i]) ? floatval(str_replace(',', '.', $_POST['descuento'][$i])) : 0;

        if (!in_array($tipo, ['producto', 'servicio']) || !$itemId) {
            continue;
        }

        $productoId = $tipo === 'producto' ? $itemId : null;
        $servicioId = $tipo === 'servicio' ? $itemId : null;

        $subtotalBruto = $precio * $cantidad;
        $subtotal = $subtotalBruto - ($descuento > 0 && $descuento <= 100 ? ($subtotalBruto * ($descuento / 100)) : 0);

        // Insertar detalle
        $stmtDetalle->execute([
            $ventaId,
            $tipo,
            $productoId,
            $servicioId,
            $cantidad,
            $precio,
            $descuento,
            $subtotal
        ]);

        // Actualizar stock si es producto
        if ($tipo === 'producto') {
            $stmtStock = $pdo->prepare("SELECT stock FROM productos WHERE id = ?");
            $stmtStock->execute([$productoId]);
            $producto = $stmtStock->fetch(PDO::FETCH_ASSOC);

            if (!$producto) {
                throw new Exception("Producto con ID $productoId no encontrado.");
            }

            if ($producto['stock'] < $cantidad) {
                throw new Exception("Stock insuficiente para el producto con ID $productoId.");
            }

            $stmtUpdateStock = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
            $stmtUpdateStock->execute([$cantidad, $productoId]);
        }
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Venta registrada exitosamente.'
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'success' => false,
        'message' => 'Error al registrar venta: ' . $e->getMessage()
    ]);
}
?>