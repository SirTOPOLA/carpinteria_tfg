<?php
require '../config/conexion.php';
header('Content-Type: application/json');

$stmt = $pdo->query("SELECT id, nombre, precio_unitario FROM productos WHERE stock > 0");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
