<?php
header('Content-Type: application/json');
include('../config/conexion.php');
 

$response = ['success' => false, 'message' => ''];

function validarFecha($fecha) {
    return $fecha === '' || preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha);
}

try {
    // Sanitizar y validar entradas
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $estado = $_POST['estado'] ?? 'pendiente';
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $fecha_entrega = $_POST['fecha_entrega'] ?? null;

    // Validar campos obligatorios
    if ($nombre === '') {
        throw new Exception('El nombre del proyecto es obligatorio.');
    }

    // Validar estado
    $estados_permitidos = ['pendiente', 'en diseño', 'en producción', 'finalizado'];
    if (!in_array($estado, $estados_permitidos)) {
        throw new Exception('El estado del proyecto no es válido.');
    }

    // Validar fechas
    if (!validarFecha($fecha_inicio)) {
        throw new Exception('La fecha de inicio no tiene el formato válido (YYYY-MM-DD).');
    }
    if (!validarFecha($fecha_entrega)) {
        throw new Exception('La fecha de entrega no tiene el formato válido (YYYY-MM-DD).');
    }

    // Insertar en la base de datos
    $stmt = $pdo->prepare("
        INSERT INTO proyectos (nombre, descripcion, estado, fecha_inicio, fecha_entrega) 
        VALUES (:nombre, :descripcion, :estado, :fecha_inicio, :fecha_entrega)
    ");
    $stmt->execute([
        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':estado' => $estado,
        ':fecha_inicio' => $fecha_inicio ?: null,
        ':fecha_entrega' => $fecha_entrega ?: null,
    ]);

    $response['success'] = true;
    $response['message'] = 'Proyecto guardado exitosamente.';
    $response['proyecto_id'] = $pdo->lastInsertId();

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
