<?php
header('Content-Type: application/json');
require_once '../config/conexion.php';

function limpiarDato($dato) {
    return trim(htmlspecialchars($dato, ENT_QUOTES, 'UTF-8'));
}

function generarCodigoAcceso($nombre, $id) {
    $nombre = strtoupper($nombre);
    $partes = explode(' ', $nombre);
    $iniciales = substr($partes[0] ?? '', 0, 2) . substr($partes[1] ?? $partes[0], 0, 2);
    $anio = date('y');
    $codigo = $iniciales . $anio . str_pad($id, 3, '0', STR_PAD_LEFT);
    return substr($codigo, 0, 9); // Siempre 9 caracteres
}

$respuesta = [
    'ok' => false,
    'mensaje' => 'Error desconocido.'
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $respuesta['mensaje'] = 'Método no permitido.';
    echo json_encode($respuesta);
    exit;
}

$nombre    = limpiarDato($_POST['nombre'] ?? '');
$correo    = limpiarDato($_POST['correo'] ?? '');
$telefono  = limpiarDato($_POST['telefono'] ?? '');
$codigo    = limpiarDato($_POST['codigo'] ?? '');
$direccion = limpiarDato($_POST['direccion'] ?? '');

if ($nombre === '' || $codigo === '') {
    $respuesta['mensaje'] = 'Nombre y DIP (código) son obligatorios.';
    echo json_encode($respuesta);
    exit;
}

try {
    // 1. Insertamos sin codigo_acceso
    $sql = "INSERT INTO clientes (nombre, email, telefono, codigo, direccion, creado_en)
            VALUES (:nombre, :correo, :telefono, :codigo, :direccion, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':codigo', $codigo);
    $stmt->bindParam(':direccion', $direccion);

    if ($stmt->execute()) {
        $id = $pdo->lastInsertId();

        // 2. Generamos el código con el ID
        $codigo_acceso = generarCodigoAcceso($nombre, $id);

        // 3. Actualizamos el campo codigo_acceso
        $sqlUpdate = "UPDATE clientes SET codigo_acceso = :codigo_acceso WHERE id = :id";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':codigo_acceso', $codigo_acceso);
        $stmtUpdate->bindParam(':id', $id);
        $stmtUpdate->execute();

        $respuesta['ok'] = true;
        $respuesta['mensaje'] = 'Cliente registrado correctamente.';
        $respuesta['codigo_acceso'] = $codigo_acceso;
    } else {
        $respuesta['mensaje'] = 'No se pudo registrar el cliente.';
    }
} catch (PDOException $e) {
    $respuesta['mensaje'] = 'Error de base de datos: ' . $e->getMessage();
}

echo json_encode($respuesta);
