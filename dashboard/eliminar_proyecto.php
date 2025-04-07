<?php
require_once '../includes/conexion.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: proyectos.php?error=ID inválido");
    exit;
}

// Verificar si el proyecto existe
$stmt = $pdo->prepare("SELECT id FROM proyectos WHERE id = :id");
$stmt->execute([':id' => $id]);
$proyecto = $stmt->fetch();

if (!$proyecto) {
    header("Location: proyectos.php?error=Proyecto no encontrado");
    exit;
}

// Eliminar proyecto
$stmt = $pdo->prepare("DELETE FROM proyectos WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: proyectos.php?mensaje=Proyecto eliminado");
exit;
