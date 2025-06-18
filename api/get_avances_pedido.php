<?php

header('Content-Type: application/json');
include('../config/conexion.php');

// Validar y sanitizar el ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
  http_response_code(400);
  echo json_encode(['error' => 'ID de pedido no válido']);
  exit;
}

// Consulta del pedido
$sql = "SELECT 
          p.id,
          p.proyecto,
          p.fecha_solicitud,
          p.fecha_entrega,
          p.descripcion,
          p.precio_obra,
          p.adelanto,
          p.estado,
          p.avance,
          p.piezas,
          s.nombre AS servicio
        FROM pedidos p
        LEFT JOIN servicios s ON p.servicio_id = s.id
        WHERE p.id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$pedido = $stmt->fetch();

if ($pedido) {
  echo json_encode($pedido);
} else {
  http_response_code(404);
  echo json_encode(['error' => 'Pedido no encontrado']);
}
