<?php
header('Content-Type: application/json');
require '../config/conexion.php'; // Asegúrate que existe y retorna un objeto PDO

try {
    if (empty($_POST['id']) || empty($_POST['proyecto_id']) || empty($_POST['responsable_id']) || empty($_POST['fecha_inicio']) || empty($_POST['estado'])) {
        throw new Exception('Todos los campos obligatorios deben estar completos.');
    }

 

    $stmt = $pdo->prepare("UPDATE producciones 
                           SET proyecto_id = ?, responsable_id = ?, fecha_inicio = ?, fecha_fin = ?, estado = ?
                           WHERE id = ?");

    $stmt->execute([
        $_POST['proyecto_id'],
        $_POST['responsable_id'],
        $_POST['fecha_inicio'],
        $_POST['fecha_fin'] ?: null,
        $_POST['estado'],
        $_POST['id']
    ]);

    echo json_encode(['success' => true, 'message' => 'Producción actualizada correctamente.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
