<?php
require_once("../includes/conexion.php");

// Validar el parámetro ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: ventas.php?error=ID inválido");
    exit;
}

// Verificar que la venta exista
$stmt = $pdo->prepare("SELECT id FROM ventas WHERE id = :id");
$stmt->execute([':id' => $id]);
$venta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$venta) {
    header("Location: ventas.php?error=Venta no encontrada");
    exit;
}

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // Eliminar detalles de la venta
    $stmt = $pdo->prepare("DELETE FROM detalle_venta WHERE venta_id = :id");
    $stmt->execute([':id' => $id]);

    // Eliminar la venta principal
    $stmt = $pdo->prepare("DELETE FROM ventas WHERE id = :id");
    $stmt->execute([':id' => $id]);

    $pdo->commit();
    header("Location: ventas.php?mensaje=Venta eliminada correctamente");
} catch (Exception $e) {
    $pdo->rollBack();
    header("Location: ventas.php?error=Ocurrió un error al eliminar");
}
