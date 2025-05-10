<?php
require_once '../includes/conexion.php';


// Validación básica
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar los campos principales
    $proveedor_id = isset($_POST['proveedor_id']) ? (int) $_POST['proveedor_id'] : 0;
    $fecha = $_POST['fecha'];
    $material_id = $_POST['material_id'] ?? [];
    $cantidad = $_POST['cantidad'] ?? [];
    $precio_unitario = $_POST['precio_unitario'] ?? [];

    // Validación básica
    if ($proveedor_id <= 0 || empty($fecha) || empty($material_id) || count($material_id) !== count($cantidad) || count($material_id) !== count($precio_unitario)) {
        die('Datos incompletos o inválidos.');
    }

    try {
        // Iniciar transacción
        $pdo->beginTransaction();

        // Calcular total de la compra
        $total = 0;
        foreach ($material_id as $i => $mat_id) {
            $qty = (float) $cantidad[$i];
            $price = (float) $precio_unitario[$i];
            $total += $qty * $price;
        }

        // Insertar en tabla compras
        $stmt = $pdo->prepare("INSERT INTO compras (proveedor_id, fecha, total) VALUES (?, ?, ?)");
        $stmt->execute([$proveedor_id, $fecha, $total]);
        $compra_id = $pdo->lastInsertId();

        // Insertar cada material en detalles_compra
        $stmt_detalle = $pdo->prepare("INSERT INTO detalles_compra (compra_id, material_id, cantidad, precio_unitario )
            VALUES (?, ?, ?, ?)");

        // Actualizar stock del material
        $stmt_update = $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual + ? WHERE id = ?");

        foreach ($material_id as $i => $mat_id) {
            $mat_id = (int) $mat_id;
            $qty = (float) $cantidad[$i];
            $price = (float) $precio_unitario[$i];
            $subtotal = $qty * $price;

            // Guardar detalle
            $stmt_detalle->execute([$compra_id, $mat_id, $qty, $price]);
            // Actualizar stock del material
            $stmt_update->execute([$qty, $mat_id]);
        }

        // Confirmar transacción
        $pdo->commit();


        // Ahora $materiales es un array asociativo con: id, material (nombre del material), y categoria
        header('location: ../dashboard/compras.php');
    } catch (PDOException $e) {
        // Revertir si hay error
        $pdo->rollBack();
        die("Error al registrar la compra: " . $e->getMessage());
    }
}
