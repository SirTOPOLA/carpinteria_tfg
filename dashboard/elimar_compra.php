<?php
require_once("../includes/conexion.php");

// Validar ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: compras.php?error=ID inválido");
    exit;
}

// Verificar si la compra existe
$stmt = $pdo->prepare("SELECT * FROM compras WHERE id = :id");
$stmt->execute([':id' => $id]);
$compra = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$compra) {
    header("Location: compras.php?error=Compra no encontrada");
    exit;
}

// Eliminar compra
$stmt = $pdo->prepare("DELETE FROM compras WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: compras.php?mensaje=Compra eliminada correctamente");
exit;
?>
