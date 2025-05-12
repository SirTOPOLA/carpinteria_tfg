<?php
require_once('../config/conexion.php');
header('Content-Type: application/json');
try {
   

    // Validaciones básicas
    $nombre = trim($_POST['nombre'] ?? '');
    if ($nombre === '') {
        throw new Exception('El nombre es obligatorio.');
    }

    $stmt = $pdo->prepare("INSERT INTO empleados 
        (nombre, apellido, genero, fecha_nacimiento, telefono, email, direccion, fecha_ingreso, horario_trabajo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['genero'],
        $_POST['fecha_nacimiento'],
        $_POST['telefono'],
        $_POST['email'],
        $_POST['direccion'],
        $_POST['fecha_ingreso'],
        $_POST['horario_trabajo']
    ]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
