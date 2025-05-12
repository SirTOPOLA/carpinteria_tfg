<?php
require_once("../config/conexion.php");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

// Validaciones básicas
if (!isset($data['usuario'], $data['password'], $data['rol'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Datos incompletos']);
    exit;
}

$usuario = trim($data['usuario']);
$password = password_hash($data['password'], PASSWORD_DEFAULT);
$rol = (int)$data['rol'];
$empleado_id = !empty($data['empleado_id']) ? (int)$data['empleado_id'] : null;

try {
    $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, password, rol_id, empleado_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$usuario, $password, $rol, $empleado_id]);

    echo json_encode(['ok' => true, 'mensaje' => 'Usuario registrado con éxito.']);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'mensaje' => 'Error al registrar usuario: ' . $e->getMessage()]);
}
