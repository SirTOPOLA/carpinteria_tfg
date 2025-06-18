<?php
// api/telefono.php
header('Content-Type: application/json');

require '../config/conexion.php'; // Asegúrate que $pdo está definido ahí

try {
    $stmt = $pdo->query("SELECT telefono FROM configuracion LIMIT 1");
    $telefono = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'telefono' => $telefono ? $telefono['telefono'] : null
    ]);
} catch (PDOException $e) {
    http_response_code(500); // Error del servidor
    echo json_encode([
        'error' => 'Error al obtener el teléfono',
        'detalles' => $e->getMessage() // Puedes quitar esto en producción
    ]);
}
