<?php
header('Content-Type: application/json');
require_once '../config/conexion.php'; // o tu archivo de conexión PDO

try {
    // Sanitizar y validar datos
    $nombre    = trim($_POST['nombre'] ?? '');
    $correo    = trim($_POST['correo'] ?? '');
    $contacto  = trim($_POST['contacto'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    if ($nombre === '') {
        throw new Exception('El nombre es obligatorio.');
    }

    $sql = "INSERT INTO proveedores (nombre, email, contacto, telefono, direccion) 
            VALUES (:nombre, :correo, :contacto, :telefono, :direccion)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre'    => $nombre,
        ':correo'    => $correo ?: null,
        ':contacto'  => $contacto ?: null,
        ':telefono'  => $telefono ?: null,
        ':direccion' => $direccion ?: null
    ]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
