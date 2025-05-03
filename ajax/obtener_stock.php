<?php
// Establecer cabecera JSON
header('Content-Type: application/json');

// Incluir conexión con la ruta correcta desde /ajax/
require_once('../includes/conexion.php');

// Validar parámetro ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Parámetro inválido.',
        'stock_actual' => 0
    ]);
    exit;
}

$material_id = (int) $_GET['id'];

try {
    // Preparar y ejecutar consulta
    $stmt = $pdo->prepare("SELECT stock_actual FROM materiales WHERE id = ?");
    $stmt->execute([$material_id]);

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode([
            'success' => true,
            'stock' => (int) $row['stock_actual']
        ]);
    } else {
        // Material no encontrado
        echo json_encode([
            'success' => false,
            'message' => 'Material no encontrado.',
            'stock' => 0
        ]);
    }
} catch (PDOException $e) {
    // Error en base de datos
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos: ' . $e->getMessage(),
        'stock' => 0
    ]);
}
