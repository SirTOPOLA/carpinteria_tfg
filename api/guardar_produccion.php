<?php
header('Content-Type: application/json');
require '../config/conexion.php';

 
try {
    // Validar campos obligatorios
    if (empty($_POST['proyecto_id']) || empty($_POST['responsable_id']) || empty($_POST['fecha_inicio']) || empty($_POST['estado'])) {
        throw new Exception('Todos los campos obligatorios deben estar completos.');
    }
 

    $stmt = $pdo->prepare("INSERT INTO producciones (proyecto_id, responsable_id, fecha_inicio, fecha_fin, estado)
                           VALUES (?, ?, ?, ?, ?)");

    $stmt->execute([
        $_POST['proyecto_id'],
        $_POST['responsable_id'],
        $_POST['fecha_inicio'],
        $_POST['fecha_fin'] ?: null, // Permite null si está vacío
        $_POST['estado']
    ]);

    echo json_encode(['success' => true, 'message' => 'Producción registrada correctamente.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
