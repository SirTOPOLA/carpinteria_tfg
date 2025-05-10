<?php
require_once '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acceso no permitido.");
}

try {
    $pdo->beginTransaction();

    $compra_id = (int) $_POST['compra_id'];
    $proveedor_id = (int) $_POST['proveedor_id'];
    $fecha = $_POST['fecha'];

    // Validar fecha segura (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        throw new Exception("Fecha no válida.");
    }

    // Actualizar la tabla compras
    $stmt = $pdo->prepare("UPDATE compras SET proveedor_id = ?, fecha = ? WHERE id = ?");
    $stmt->execute([$proveedor_id, $fecha, $compra_id]);

    // Actualizar detalles
    $detalle_ids = $_POST['detalle_ids'];
    $material_ids = $_POST['material_ids'];
    $cantidades = $_POST['cantidades'];
    $precios = $_POST['precios']; 

    $total = 0;

    for ($i = 0; $i < count($detalle_ids); $i++) {
        $did = (int)$detalle_ids[$i];
        $mid = (int)$material_ids[$i];
        $cantidad = max(1, (int)$cantidades[$i]);
        $precio = round((float)$precios[$i], 2);
       // $stock_minimo = max(0, (int)$stocks_minimos[$i]);

        $subtotal = $cantidad * $precio;
        $total += $subtotal;

        $stmt = $pdo->prepare("
            UPDATE detalles_compra
            SET material_id = ?, cantidad = ?, precio_unitario = ? 
            WHERE id = ? AND compra_id = ?
        ");
        $stmt->execute([$mid, $cantidad, $precio,  $did, $compra_id]);
    }

    // Actualizar total calculado
    $stmt = $pdo->prepare("UPDATE compras SET total = ? WHERE id = ?");
    $stmt->execute([$total, $compra_id]);

    $pdo->commit();
    header("Location: ../dashboard/compras.php");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("Error al guardar: " . htmlspecialchars($e->getMessage()));
}
