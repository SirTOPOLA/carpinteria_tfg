<?php
require_once '../includes/conexion.php';

header('Content-Type: application/json');

$tipo = $_GET['tipo'] ?? '';

try {
    if ($tipo === 'entrada'  || $tipo === 'salida') {
        $stmt = $pdo->query("SELECT id, nombre FROM materiales ORDER BY nombre ASC");
        $materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    /* } elseif ($tipo === 'salida') {
        $sql = "SELECT DISTINCT m.id, m.nombre
                FROM detalles_compra dc
                INNER JOIN materiales m ON dc.material_id = m.id
                WHERE dc.cantidad > 0
                ORDER BY m.nombre ASC";
        $stmt = $pdo->query($sql);
        $materiales = $stmt->fetchAll(PDO::FETCH_ASSOC); */
    } else {
        throw new Exception("Tipo de movimiento no válido");
    }

    echo json_encode(['success' => true, 'materiales' => $materiales]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
