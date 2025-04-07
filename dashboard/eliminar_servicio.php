<?php
require_once("../includes/conexion.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verificar ID válido
if ($id <= 0) {
    header("Location: servicios.php?error=ID inválido");
    exit;
}

// Verificar si el servicio existe
$stmt = $pdo->prepare("SELECT id FROM servicios WHERE id = :id");
$stmt->execute([':id' => $id]);

if (!$stmt->fetch()) {
    header("Location: servicios.php?error=Servicio no encontrado");
    exit;
}

// Eliminar servicio
$stmt = $pdo->prepare("DELETE FROM servicios WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: servicios.php?mensaje=Servicio eliminado correctamente");
exit;
