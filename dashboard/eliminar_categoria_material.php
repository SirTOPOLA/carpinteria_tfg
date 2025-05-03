<?php
require_once("../includes/conexion.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID inválido.");
}

// Validar si existe
$stmt = $pdo->prepare("SELECT * FROM categorias_materiales WHERE id = :id");
$stmt->execute([':id' => $id]);
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    die("Categoría no encontrada.");
}

// Eliminar
$stmt = $pdo->prepare("DELETE FROM categorias_materiales WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: categorias_material.php?mensaje=Categoría eliminada");
exit;
?>
