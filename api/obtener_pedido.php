<?php
require '../config/conexion.php';
header('Content-Type: application/json; charset=utf-8');
 
$pedido_id = isset($_GET['pedido_id']) ? (int) $_GET['pedido_id'] : 0;

$sql = "
SELECT 
    p.*, 
    c.nombre AS cliente,
    s.nombre AS servicio,
    0 AS descuento
FROM pedidos p
JOIN clientes c ON p.cliente_id = c.id
LEFT JOIN servicios s ON p.servicio_id = s.id
JOIN estados e ON p.estado_id = e.id
WHERE p.id = :pedido_id AND e.nombre = 'finalizado' AND e.entidad = 'pedido'
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['pedido_id' => $pedido_id]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if ($pedido) {
    echo json_encode([
        'cliente' => $pedido['cliente'],
        'adelanto' => number_format($pedido['adelanto'], 2, '.', ''),
        'estimacion_total' => number_format($pedido['estimacion_total'], 2, '.', ''),
        'servicio' => $pedido['servicio'],
        'piezas' => $pedido['piezas'],
        'precio_obra' => number_format($pedido['precio_obra'], 2, '.', ''),
        'descuento' => number_format($pedido['descuento'], 2, '.', '')
    ]);
} else {
    echo json_encode(['error' => 'Pedido no encontrado']);
}
