<?php
require '../config/conexion.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'], $data['nuevo_estado'])) {
  echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
  exit;
}

$id = intval($data['id']);
$estado = $data['nuevo_estado'] ? 1 : 0;

try {
  $stmt = $pdo->prepare("UPDATE servicios SET activo = ? WHERE id = ?");
  $stmt->execute([$estado, $id]);
  echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Error al actualizar estado']);
}
