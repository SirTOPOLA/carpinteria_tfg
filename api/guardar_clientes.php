<?php
require '../config/conexion.php';
header('Content-Type: application/json');

// Validación básica
if (
    empty($_POST['nombre']) ||
    !isset($_POST['precio_base']) ||
    empty($_POST['unidad'])
) {
    echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios.']);
    exit;
}

// Sanitización
$nombre = trim($_POST['nombre']);
$descripcion = trim($_POST['descripcion'] ?? '');
$precio_base = is_numeric($_POST['precio_base']) ? floatval($_POST['precio_base']) : null;
$unidad = trim($_POST['unidad']);
$activo = isset($_POST['activo']) && $_POST['activo'] ? 1 : 0;

// Validación de precio
if ($precio_base === null || $precio_base < 0) {
    echo json_encode(['success' => false, 'message' => 'Precio inválido.']);
    exit;
}

try {
    // Verificar si ya existe un servicio con ese nombre
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM servicios WHERE nombre = ?");
    $stmt->execute([$nombre]);
    $existe = $stmt->fetchColumn();

    if ($existe > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe un servicio con ese nombre.']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO servicios (nombre, descripcion, precio_base, unidad, activo) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $descripcion, $precio_base, $unidad, $activo]);

    echo json_encode(['success' => true, 'message' => 'Servicio registrado correctamente.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al guardar el servicio.']);
}
