<?php
require_once '../config/conexion.php';

header('Content-Type: application/json');

$tipo = $_GET['tipo'] ?? '';
$id = $_GET['id'] ?? '';
$materiales = [];
$stock = '';
try {
    if ($tipo === 'entrada' || $tipo === 'salida' || $tipo === 'pendiente') {
        $stmt = $pdo->query("SELECT id, nombre FROM materiales ORDER BY nombre ASC");
        $materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    if ($id) {
        $stmt = $pdo->prepare("SELECT stock_actual FROM materiales WHERE id = ?");
        $stmt->execute([$id]);
        $material_stock = $stmt->fetch(PDO::FETCH_ASSOC);
        $stock = $material_stock['stock_actual'];

    }

    echo json_encode([
        'success' => true,
        'materiales' => $materiales,
        'stock' => $stock 
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
