<?php
require_once('../config/conexion.php');
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'], $data['usuario'], $data['rol'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Datos incompletos']);
    exit;
}

$id = (int)$data['id'];
$usuario = trim($data['usuario']);
$password = trim($data['password']);
$rol = (int)$data['rol'];
$empleado_id = !empty($data['empleado_id']) ? (int)$data['empleado_id'] : null;

try {
    if ($password !== '') {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET usuario = ?, password = ?, rol_id = ?, empleado_id = ? WHERE id = ?";
        $params = [$usuario, $passwordHash, $rol, $empleado_id, $id];
    } else {
        $sql = "UPDATE usuarios SET usuario = ?, rol_id = ?, empleado_id = ? WHERE id = ?";
        $params = [$usuario, $rol, $empleado_id, $id];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['ok' => true]);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
}
