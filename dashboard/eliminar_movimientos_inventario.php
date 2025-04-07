<?php
require_once "../includes/conexion.php";

// Validar ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    die("ID inválido.");
}

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // Obtener movimiento
    $stmt = $pdo->prepare("SELECT * FROM movimientos_inventario WHERE id = ?");
    $stmt->execute([$id]);
    $movimiento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$movimiento) {
        throw new Exception("Movimiento no encontrado.");
    }

    // Obtener stock actual del material
    $stmt = $pdo->prepare("SELECT stock FROM materiales WHERE id = ?");
    $stmt->execute([$movimiento['material_id']]);
    $material = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$material) {
        throw new Exception("Material no encontrado.");
    }

    $stock_actual = $material['stock'];
    $nuevo_stock = $stock_actual;

    // Revertir efecto del movimiento
    if ($movimiento['tipo'] === 'entrada') {
        $nuevo_stock -= $movimiento['cantidad'];
        if ($nuevo_stock < 0) {
            throw new Exception("No se puede eliminar: el stock quedaría negativo.");
        }
    } else { // salida
        $nuevo_stock += $movimiento['cantidad'];
    }

    // Actualizar stock
    $stmt = $pdo->prepare("UPDATE materiales SET stock = ? WHERE id = ?");
    $stmt->execute([$nuevo_stock, $movimiento['material_id']]);

    // Eliminar movimiento
    $stmt = $pdo->prepare("DELETE FROM movimientos_inventario WHERE id = ?");
    $stmt->execute([$id]);

    $pdo->commit();

    header("Location: movimientos_inventario.php?mensaje=eliminado");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    die("Error al eliminar movimiento: " . $e->getMessage());
}
?>
