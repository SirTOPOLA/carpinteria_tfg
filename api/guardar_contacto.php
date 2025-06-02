<?php
 
header('Content-Type: application/json');
include_once ('../config/conexion.php');
// Solo permitimos POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Sanear y validar entrada
function limpiar($dato) {
    return htmlspecialchars(strip_tags(trim($dato)));
}

$nombre = isset($_POST['nombre']) ? limpiar($_POST['nombre']) : '';
$codigo = isset($_POST['codigo']) ? limpiar($_POST['codigo']) : '';
$telefono = isset($_POST['telefono']) ? limpiar($_POST['telefono']) : '';
$direccion = isset($_POST['direccion']) ? limpiar($_POST['direccion']) : '';
$email = isset($_POST['email']) ? limpiar($_POST['email']) : '';
$descripcion = isset($_POST['descripcion']) ? limpiar($_POST['descripcion']) : '';

// Validaciones básicas
$errores = [];

if (empty($nombre)) {
    $errores['nombre'] = 'El nombre es obligatorio.';
}

if (empty($codigo)) {
    $errores['codigo'] = 'El código es obligatorio.';
}

if (!empty($telefono) && !preg_match('/^\+?\d{7,15}$/', $telefono)) {
    $errores['telefono'] = 'El teléfono no es válido. Solo números y opcional "+".';
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores['email'] = 'El correo electrónico no es válido.';
}

if (empty($descripcion)) {
    $errores['descripcion'] = 'La descripción es obligatoria.';
}

if (count($errores) > 0) {
    http_response_code(422); // Unprocessable Entity
    echo json_encode(['errores' => $errores]);
    exit;
}

// Aquí puedes hacer lo que necesites, ej: guardar en base de datos o enviar email

// Ejemplo simple: guardar en archivo logs.txt (modo append)
$datos_guardar = "Nombre: $nombre | Código: $codigo | Teléfono: $telefono | Dirección: $direccion | Email: $email | Descripción: $descripcion" . PHP_EOL;
file_put_contents('logs.txt', $datos_guardar, FILE_APPEND | LOCK_EX);

// Respuesta exitosa
echo json_encode(['mensaje' => 'Datos recibidos correctamente']);
exit;
