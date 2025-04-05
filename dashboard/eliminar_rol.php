<?php
require_once("../includes/conexion.php");

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: roles.php?error=ID inválido");
    exit;
}

// Se podría agregar validación si el rol está asignado a algún usuario antes de eliminarlo

$delete = $pdo->prepare("DELETE FROM roles WHERE id = ?");
$delete->execute([$id]);

header("Location: roles.php?mensaje=Rol eliminado");
exit;
