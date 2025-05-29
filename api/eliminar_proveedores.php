<?php
require_once '../config/conexion.php'; // Ajusta la ruta

header('Content-Type: application/json');

$servicio_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($servicio_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID no válido']);
    exit;
}

try {
    $sql = "DELETE FROM proveedores WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$servicio_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Servicio eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró el servicio o ya fue eliminado']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el servicio']);
}
