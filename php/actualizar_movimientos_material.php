<?php
require_once "../includes/conexion.php";


// Validar si se está procesando el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $material_id = isset($_POST['material_id']) ? (int) $_POST['material_id'] : 0;
    $tipo = isset($_POST['tipo']) ? trim($_POST['tipo']) : '';
    $cantidad = isset($_POST['cantidad']) ? (int) $_POST['cantidad'] : 0;

    if ($id <= 0 || $material_id <= 0 || !in_array($tipo, ['entrada', 'salida']) || $cantidad <= 0) {
        die("Datos inválidos.");
    }

    $stmt = $pdo->prepare("SELECT * FROM movimientos_material WHERE id = ?");
    $stmt->execute([$id]);
    $movimiento_anterior = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$movimiento_anterior) die("Movimiento no encontrado.");

    $stmt = $pdo->prepare("SELECT stock_actual FROM materiales WHERE id = ?");
    $stmt->execute([$material_id]);
    $material = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$material) die("Material no encontrado.");

    $stock_actual = $material['stock_actual'];
    $stock_ajustado = $stock_actual;

    if ($movimiento_anterior['tipo_movimiento'] === 'entrada') {
        $stock_ajustado -= $movimiento_anterior['cantidad'];
    } else {
        $stock_ajustado += $movimiento_anterior['cantidad'];
    }

    if ($tipo === 'entrada') {
        $nuevo_stock = $stock_ajustado + $cantidad;
    } else {
        if ($cantidad > $stock_ajustado) die("No hay suficiente stock para esta salida.");
        $nuevo_stock = $stock_ajustado - $cantidad;
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE materiales SET stock_actual = ? WHERE id = ?");
        $stmt->execute([$nuevo_stock, $material_id]);

        $stmt = $pdo->prepare("UPDATE movimientos_material SET material_id = ?, tipo_movimiento = ?, cantidad = ?, fecha = NOW() WHERE id = ?");
        $stmt->execute([$material_id, $tipo, $cantidad, $id]);

        $pdo->commit();
        header("Location: ../dashboard/movimientos_material.php ");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error al actualizar: " . $e->getMessage());
    }
}
?>