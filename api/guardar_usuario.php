<?php
require_once("../config/conexion.php");
header("Content-Type: application/json");

$errores = [];

// Validaciones iniciales
$usuario = trim($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? '';
$rol = isset($_POST['rol']) ? (int)$_POST['rol'] : null;
$empleado_id = !empty($_POST['empleado_id']) ? (int)$_POST['empleado_id'] : null;

if ($usuario === '' || !filter_var($usuario, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El usuario debe ser un correo electrónico válido.';
}

if (strlen($password) < 6) {
    $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
}
if (!preg_match('/[A-Z]/', $password)) {
    $errores[] = 'La contraseña debe contener al menos una letra mayúscula.';
}
if (!preg_match('/[a-z]/', $password)) {
    $errores[] = 'La contraseña debe contener al menos una letra minúscula.';
}
if (!preg_match('/\d/', $password)) {
    $errores[] = 'La contraseña debe contener al menos un número.';
}
if (!preg_match('/[^a-zA-Z\d]/', $password)) {
    $errores[] = 'La contraseña debe contener al menos un carácter especial.';
}

// Validación del rol
if (!$rol) {
    $errores[] = 'Debe seleccionar un rol para el usuario.';
}

// Validación de duplicado por email (usuario)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE LOWER(usuario) = LOWER(?)");
$stmt->execute([$usuario]);
if ($stmt->fetchColumn() > 0) {
    $errores[] = 'Ya existe un usuario con ese correo electrónico.';
}

// Si hay errores, retornarlos al frontend
if (!empty($errores)) {
    echo json_encode(['status' => false, 'message' => $errores]);
    exit;
}

// Subida de imagen (opcional)
$carpeta_destino = 'uploads/usuarios/';
if (!file_exists($carpeta_destino)) {
    mkdir($carpeta_destino, 0755, true);
}

$imagen_path = null;
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
    $nombre_img = 'usuario_' . time() . '.' . $ext;
    $ruta_img = $carpeta_destino . $nombre_img;

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_img)) {
        $imagen_path = $ruta_img;
    } else {
        echo json_encode(['status' => false, 'message' => 'Error al subir la imagen.']);
        exit;
    }
}

// Inserción segura
try {
    $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, password, rol_id, empleado_id, perfil) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $usuario,
        password_hash($password, PASSWORD_DEFAULT),
        $rol,
        $empleado_id,
        $imagen_path
    ]);

    echo json_encode(['status' => true, 'message' => 'Usuario registrado con éxito.']);
} catch (Exception $e) {
    echo json_encode(['status' => false, 'message' => 'Error al registrar usuario: ' . $e->getMessage()]);
}
