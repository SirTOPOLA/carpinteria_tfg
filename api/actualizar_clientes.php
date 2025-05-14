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
$id    = trim($_POST['id'] ?? '');
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
    $email = $email ?: null;
    $telefono = $telefono ?: null;
    $direccion = $direccion ?: null;
    $codigo = $codigo ?: null;

    $sql = "UPDATE clientes SET 
            nombre = :nombre,
            email = :email,
            telefono = :telefono,
            direccion = :direccion,
            codigo  = :codigo 
        WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
    $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
    $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();


    $_SESSION['exito'] = 'Cliente actualizado correctamente.';
    header('Location: clientes.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['alerta'] = 'Error al actualizar el cliente.';
    header("Location: editar_cliente.php?id=" . $id);
    exit;
}
