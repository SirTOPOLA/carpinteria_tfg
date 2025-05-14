<?php
header('Content-Type: application/json');
require_once '../config/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Solicitud no válida. Solo se permite POST.'
    ]);
    exit;
}

 

// Saneamiento de entrada
$id     = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nombre = trim($_POST['nombre'] ?? '');

// Inicializar errores
$errores = [];

// Validación del ID
if (!$id || $id <= 0) {
    $errores[] = 'ID inválido o ausente.';
}

// Validación del nombre
if ($nombre === '') {
    $errores[] = 'El nombre del rol es obligatorio.';
    
} elseif (mb_strlen($nombre) < 3) {
    $errores[] = 'El nombre debe tener al menos 3 caracteres.';
    
} elseif (!preg_match('/^[a-zA-ZÁÉÍÓÚáéíóúñÑ0-9\s\-]+$/u', $nombre)) {
    $errores[] = 'El nombre contiene caracteres no permitidos.';
    
}

// Verificar duplicado (case-insensitive)
if (empty($errores)) {
    $stmt = $pdo->prepare("SELECT id FROM roles WHERE LOWER(nombre) = LOWER(?) AND id != ?");
    $stmt->execute([$nombre, $id]);
    if ($stmt->fetch()) {
        $errores[] = 'Ya existe un rol con ese nombre.';
    }
}

// Si hay errores, los enviamos como respuesta
if (!empty($errores)) {
    echo json_encode([
        'success' => false,
        'message' => $errores
    ]);
    exit;
}

// Sanitización adicional para evitar XSS en lo que se almacena
$nombre_sanitizado = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');

// Actualizar el rol en la base de datos
try {
    $stmt = $pdo->prepare("UPDATE roles SET nombre = ? WHERE id = ?");
    $stmt->execute([$nombre_sanitizado, $id]);

    echo json_encode([
        'success' => true,
        'message' => 'Rol actualizado correctamente.'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' =>  'Error en la base de datos: ' . $e->getMessage()]
    );
}
