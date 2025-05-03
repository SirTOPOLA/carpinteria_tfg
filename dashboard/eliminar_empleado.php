<?php
require_once("../includes/conexion.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM empleados WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: empleados.php");
exit;
