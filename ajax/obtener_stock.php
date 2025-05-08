<?php
require_once '../includes/conexion.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? null;
$tipo = $_GET['tipo'] ?? null;

if (!$id || !$tipo) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

try {
    if ($tipo === 'entrada') {
        // Stock desde tabla materiales
        $stmt = $pdo->prepare("SELECT stock_actual FROM materiales WHERE id = ?");
        $stmt->execute([$id]);
        $stock = $stmt->fetchColumn();
    } elseif ($tipo === 'salida') {
        // Sumar cantidad disponible desde detalles_compra
        $stmt = $pdo->prepare("SELECT SUM(cantidad) AS stock_actual FROM detalles_compra WHERE material_id = ?");
        $stmt->execute([$id]);
        $stock = $stmt->fetchColumn();
    } else {
        throw new Exception("Tipo de movimiento inválido.");
    }

    $stock = $stock !== null ? (int) $stock : 0;

    echo json_encode([
        'success' => true,
        'stock_actual' => $stock
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
