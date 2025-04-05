<?php
require_once("../includes/conexion.php");

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: clientes.php");
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: clientes.php?exito=2");
        exit;
    } else {
        header("Location: clientes.php?error=1");
        exit;
    }
} catch (PDOException $e) {
    header("Location: clientes.php?error=2");
    exit;
}
