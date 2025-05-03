<?php
require_once("../includes/conexion.php");

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: departamentos.php");
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM departamentos WHERE id = ?");
    $stmt->execute([$id]);
} catch (PDOException $e) {
    // Puedes registrar errores en log si lo deseas
}

header("Location: departamentos.php");
exit;
