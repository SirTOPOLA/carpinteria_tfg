<?php
require_once("../config/conexion.php");
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $id = (int) $data['id'];

    // Alternar estado
    $stmt = $pdo->prepare("UPDATE usuarios SET activo = NOT activo WHERE id = ?");
    $stmt->execute([$id]);

    // Mensaje personalizado que quieres enviar al frontend
    echo json_encode([
        'ok' => true,
        'mensaje' => 'El estado del usuario se actualizó correctamente.'
    ]);
} else {
    echo json_encode([
        'ok' => false,
        'mensaje' => 'ID no válido o faltante.'
    ]);
}
