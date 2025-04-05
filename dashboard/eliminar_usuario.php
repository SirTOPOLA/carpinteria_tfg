<?php
require_once("../includes/conexion.php");

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: usuarios.php?error=ID inválido");
    exit;
}

$delete = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
$delete->execute([$id]);

header("Location: usuarios.php?mensaje=Usuario eliminado");
exit;
