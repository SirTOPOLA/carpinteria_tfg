<?php
 // Si no hay sesión → redirige a login
 if (!isset($_SESSION['usuario']) || isset($_SESSION['usuario'])) {
    header("Location: ../index.php?vista=inicio");
    exit;
  }
session_start();
header('Content-Type: application/json');

require_once '../config/conexion.php';

$response = ['success' => false, 'message' => ''];

try {
    // Validar que la solicitud sea POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Validar y sanitizar ID
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    if ($id <= 0) {
        throw new Exception('ID de proveedor no válido.');
    }

    // Validar campos requeridos
    $nombre = trim($_POST['nombre'] ?? '');
    if (empty($nombre)) {
        throw new Exception('El nombre es obligatorio.');
    }

    // Opcionales (con sanitización básica)
    $correo = filter_var($_POST['correo'] ?? '', FILTER_VALIDATE_EMAIL) ?: null;
    $contacto = trim($_POST['contacto'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    // Verificar que el proveedor existe
    $stmt = $pdo->prepare("SELECT id FROM proveedores WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        throw new Exception('No existe proveedor con ese ID.');
    }

    // Preparar la actualización
    $sql = "UPDATE proveedores SET nombre = ?, email = ?, contacto = ?, telefono = ?, direccion = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $correo, $contacto, $telefono, $direccion, $id]);

    $response['success'] = true;
    $response['message'] = 'Proveedor actualizado correctamente.';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Responder en JSON
echo json_encode($response);
exit;
