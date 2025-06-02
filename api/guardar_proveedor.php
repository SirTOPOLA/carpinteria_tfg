<?php

header('Content-Type: application/json');
require_once '../config/conexion.php';

try {
    // Sanitizar entradas
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contacto = trim($_POST['contacto'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    // Validaciones
    if ($nombre === '') {
        throw new Exception('El nombre es obligatorio.');
    }

    if ($correo !== '' && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('El formato del correo no es válido.');
    }

    // Insertar proveedor
    $sql = "INSERT INTO proveedores (nombre, email, contacto, telefono, direccion) 
            VALUES (:nombre, :correo, :contacto, :telefono, :direccion)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':correo' => $correo ?: null,
        ':contacto' => $contacto ?: null,
        ':telefono' => $telefono ?: null,
        ':direccion' => $direccion ?: null
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Proveedor registrado correctamente.',
        'material' => [
            'id' => $pdo->lastInsertId(),
            'nombre' => $nombre
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
