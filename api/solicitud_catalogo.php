<?php
header('Content-Type: application/json');
include_once ('../config/conexion.php'); // si necesitas la conexión DB, si no puedes omitirlo

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

function limpiar($dato) {
    return htmlspecialchars(strip_tags(trim($dato)));
}

$nombre = isset($_POST['nombre']) ? limpiar($_POST['nombre']) : '';
$codigo = isset($_POST['codigo']) ? limpiar($_POST['codigo']) : '';
$telefono = isset($_POST['telefono']) ? limpiar($_POST['telefono']) : '';
$direccion = isset($_POST['direccion']) ? limpiar($_POST['direccion']) : '';
$email = isset($_POST['email']) ? limpiar($_POST['email']) : '';
$descripcion = isset($_POST['descripcion']) ? limpiar($_POST['descripcion']) : '';

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
    http_response_code(422);
    echo json_encode(['errores' => $errores]);
    exit;
}

// Guardar en log
$datos_guardar = date('Y-m-d H:i:s') . " | Nombre: $nombre | Código: $codigo | Teléfono: $telefono | Dirección: $direccion | Email: $email | Descripción: $descripcion" . PHP_EOL;
file_put_contents('logs.txt', $datos_guardar, FILE_APPEND | LOCK_EX);

// Preparar mensaje WhatsApp
// Número del destinatario (sin + ni espacios) ej: '5215512345678' para México con código país +52
$numero_destino = '5215512345678'; // Cambia por tu número real

// Mensaje para WhatsApp (url encoded)
$mensaje = "Hola,%20me%20interesa%20el%20producto/servicio%20con%20código%20$codigo.%0A";
$mensaje .= "Nombre:%20$nombre%0A";
if ($telefono) $mensaje .= "Teléfono:%20$telefono%0A";
if ($direccion) $mensaje .= "Dirección:%20$direccion%0A";
if ($email) $mensaje .= "Email:%20$email%0A";
$mensaje .= "Descripción:%20$descripcion";

$mensaje = rawurlencode($mensaje);

// URL WhatsApp API
$url_whatsapp = "https://wa.me/$numero_destino?text=$mensaje";

// Respuesta con éxito + URL WhatsApp para redirigir en frontend
echo json_encode([
    'mensaje' => 'Datos recibidos correctamente',
    'whatsapp_url' => $url_whatsapp
]);
exit;
