<?php
require '../config/conexion.php';

header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['id'], $input['nombre'], $input['precio_base'], $input['unidad'])) {
  echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
  exit;
}

$id = intval($input['id']);
$nombre = trim($input['nombre']);
$descripcion = trim($input['descripcion'] ?? '');
$precio_base = floatval($input['precio_base']);
$unidad = trim($input['unidad']);
$activo = $input['activo'] ? 1 : 0;

try {
  $stmt = $pdo->prepare("UPDATE servicios SET nombre = ?, descripcion = ?, precio_base = ?, unidad = ?, activo = ? WHERE id = ?");
  $stmt->execute([$nombre, $descripcion, $precio_base, $unidad, $activo, $id]);
  echo json_encode(['success' => true, 'message' => 'Servicio actualizado correctamente.']);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Error al actualizar el servicio.']);
}
