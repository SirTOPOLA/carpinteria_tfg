<?php
require_once '../includes/conexion.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: materiales.php?error=ID inválido");
    exit;
}

// Verificar si el material existe
$stmt = $pdo->prepare("SELECT id FROM materiales WHERE id = :id");
$stmt->execute([':id' => $id]);
$material = $stmt->fetch();

if (!$material) {
    header("Location: materiales.php?error=material no encontrado");
    exit;
}

// Eliminar material
$stmt = $pdo->prepare("DELETE FROM materiales WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: materiales.php?mensaje=material eliminado");
exit;
