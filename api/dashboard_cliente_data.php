<?php
if(session_status() == PHP_SESSION_NONE){
  session_start();
}
require '../config/conexion.php'; // tu conexión
header('Content-Type: application/json');

$cliente = $_SESSION['usuario']; // suponiendo que el cliente está logueado
$cliente_id = $cliente['id']; // suponiendo que el cliente está logueado

$response = [
  'pedidos' => [],
  'detalles' => [],
];

$sql = "SELECT 
          p.id,
          p.proyecto,
          p.fecha_solicitud,
          p.fecha_entrega,
          p.estimacion_total,
          e.nombre AS estado,
        COALESCE(SUM(a.porcentaje), 0) AS avance

        FROM pedidos p
        LEFT JOIN producciones pr ON pr.solicitud_id = p.id
        LEFT JOIN avances_produccion a ON a.produccion_id = pr.id
        JOIN estados e ON p.estado_id = e.id
        WHERE p.cliente_id = ?
        GROUP BY p.id, p.proyecto, p.fecha_solicitud, p.fecha_entrega, p.estimacion_total, e.nombre
        ORDER BY p.fecha_solicitud DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$cliente_id]);

$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$response['pedidos'] = $pedidos;



$sql = "SELECT 
          p.*,
          s.nombre AS servicio,
          e.nombre AS estado
        FROM pedidos p
        LEFT JOIN servicios s ON p.servicio_id = s.id
        JOIN estados e ON p.estado_id = e.id
        WHERE p.id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$cliente_id]);
$pedidoDeta = $stmt->fetchAll(PDO::FETCH_ASSOC);

$response['detalles'] = $pedidoDeta;

echo json_encode($response);
?>
