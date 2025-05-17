<?php
require 'conexion.php';

try {
    $pdo->beginTransaction();

    $cliente_id = $_POST['cliente_id']; 
    $fecha = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare("INSERT INTO ventas (cliente_id,  fecha) VALUES ( ?, ?)");
    $stmt->execute([$cliente_id,   $fecha]);
    $venta_id = $pdo->lastInsertId();

    $tipos = $_POST['tipo'];
    $cantidades = $_POST['cantidad'];
    $precios = $_POST['precio_unitario'];
    $producto_ids = $_POST['producto_id'] ?? [];
    $servicio_ids = $_POST['servicio_id'] ?? [];

    $total = 0;

    for ($i = 0; $i < count($tipos); $i++) {
        $tipo = $tipos[$i];
        $cantidad = (int)$cantidades[$i];
        $precio = (float)$precios[$i];

        $producto_id = ($tipo === 'producto') ? (int)$producto_ids[$i] : null;
        $servicio_id = ($tipo === 'servicio') ? (int)$servicio_ids[$i] : null;

        $stmt = $pdo->prepare("INSERT INTO detalles_venta (venta_id, tipo, producto_id, servicio_id, cantidad, precio_unitario)
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$venta_id, $tipo, $producto_id, $servicio_id, $cantidad, $precio]);

        $total += $cantidad * $precio;
    }

    $stmt = $pdo->prepare("UPDATE ventas SET total = ? WHERE id = ?");
    $stmt->execute([$total, $venta_id]);

    $pdo->commit();
    echo "Venta registrada con éxito.";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
