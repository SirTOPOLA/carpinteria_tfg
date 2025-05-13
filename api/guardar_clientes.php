<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir conexión
require_once '../config/conexion.php';

// Devolver JSON siempre
header('Content-Type: application/json');

$response = ['success' => false];

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['error'] = 'Método no permitido.';
    echo json_encode($response);
    exit;
}

// Validar y limpiar campos
$nombre    = trim($_POST['nombre'] ?? '');
$correo    = trim($_POST['correo'] ?? '');
$codigo    = trim($_POST['codigo'] ?? '');
$telefono  = trim($_POST['telefono'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');

// Validación mínima del lado del servidor
if ($nombre === '') {
    $response['error'] = 'El nombre es obligatorio.';
    echo json_encode($response);
    exit;
}

if ($correo !== '' && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $response['error'] = 'Correo electrónico inválido.';
    echo json_encode($response);
    exit;
}

try {
    $sql = "INSERT INTO clientes (nombre, email, codigo, telefono, direccion, creado_en)
            VALUES (:nombre, :correo, :codigo, :telefono, :direccion, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':codigo', $codigo);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->execute();
    $idCliente = $pdo->lastInsertId();

    // 2. Generar código DIP automático descriptivo (8 caracteres)
    // Ejemplo: A2C5J824
    $nombrePart = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $nombre), 0, 2));
    $direccionPart = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $direccion), 0, 1));
    $año = date('y'); // 2 últimos dígitos del año
    $codigo = sprintf('%s%s%s%03d', $nombrePart, $direccionPart, $año, $idCliente);

    // Asegurar que tiene exactamente 8 caracteres
    $codigo = strtoupper(substr($codigo, 0, 8));

    // 3. Actualizar cliente con el código generado
    $update = $pdo->prepare("UPDATE clientes SET codigo_acceso = :codigo WHERE id = :id");
    $update->bindParam(':codigo', $codigo);
    $update->bindParam(':id', $idCliente);
    $update->execute();

    $response['success'] = true;
    $response['codigo'] = $codigo;
 

} catch (PDOException $e) {
    $response['error'] = 'Error al guardar el cliente: ' . $e->getMessage();
}

echo json_encode($response);
