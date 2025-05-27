<?php
require_once '../config/conexion.php'; // Asegúrate de que $pdo esté definido aquí

header('Content-Type: application/json');

try {
    // Validar datos principales
    if (empty($_POST['cliente_id'])) {
        echo json_encode(['success' => false, 'message' => 'Cliente obligatorio.']);
        exit;
    }
     
    if (empty($_POST['tipo']) || !is_array($_POST['tipo'])) {
        throw new Exception("No hay detalles de venta.");
    }

    // Validaciones básicas


    $metodoPago = $_POST['metodo_pago'] ?? 'efectivo';
    $clienteId = intval($_POST['cliente_id']);
    $totalVenta = floatval($_POST['total'] ?? 0);

    // Iniciar transacción
    $pdo->beginTransaction();

    // Insertar venta
    $stmtVenta = $pdo->prepare("
        INSERT INTO ventas (cliente_id, total, metodo_pago, fecha)
        VALUES (?, ?, ?, NOW())
    ");
    $stmtVenta->execute([$clienteId, $totalVenta, $metodoPago]);

    $ventaId = $pdo->lastInsertId();

    // Insertar detalles
    $stmtDetalle = $pdo->prepare("
        INSERT INTO detalles_venta (
            venta_id, tipo, producto_id, servicio_id, cantidad, precio_unitario, descuento, subtotal
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($_POST['tipo'] as $i => $tipo) {
        $tipo = trim($tipo);
        $cantidad = isset($_POST['cantidad'][$i]) ? intval($_POST['cantidad'][$i]) : 1;
        $precio = isset($_POST['precio_unitario'][$i]) ? floatval($_POST['precio_unitario'][$i]) : 0;
        $descuento = isset($_POST['descuento'][$i]) ? floatval($_POST['descuento'][$i]) : 0;

        if (!in_array($tipo, ['producto', 'servicio'])) {
            continue; // Saltar si no es válido
        }

        $productoId = $tipo === 'producto' ? intval($_POST['producto_id'][$i]) : null;
        $servicioId = $tipo === 'servicio' ? intval($_POST['servicio_id'][$i]) : null;

        $subtotal = ($precio * $cantidad) - $descuento;

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
