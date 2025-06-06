<?php
require '../config/conexion.php';
header('Content-Type: application/json');

// Capturar datos del formulario
$produccion_id = $_POST['produccion_id'] ?? null;
$descripcion = trim($_POST['descripcion'] ?? '');
$responsable_id = $_POST['responsable_id'] ?? null;
$fecha_inicio = $_POST['fecha_inicio'] ?? null;
$fecha_fin = $_POST['fecha_fin'] ?? null;

// Validación básica
if (!$produccion_id || !$descripcion || !$responsable_id || !$fecha_inicio || !$fecha_fin) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

// Obtener el ID del estado "pendiente" para tareas de producción
$stmtEstadoPendiente = $pdo->prepare("SELECT id FROM estados WHERE nombre = 'pendiente' AND entidad = 'produccion' LIMIT 1");
$stmtEstadoPendiente->execute();
$estadoPendiente = $stmtEstadoPendiente->fetch(PDO::FETCH_ASSOC);

if (!$estadoPendiente) {
    echo json_encode(['success' => false, 'message' => 'Estado "pendiente" no definido para producción']);
    exit;
}
$estado_id_pendiente = $estadoPendiente['id'];

// Obtener el ID del estado "en_proceso" para producción
$stmtEstadoProceso = $pdo->prepare("SELECT id FROM estados WHERE nombre = 'en_proceso' AND entidad = 'produccion' LIMIT 1");
$stmtEstadoProceso->execute();
$estadoEnProceso = $stmtEstadoProceso->fetch(PDO::FETCH_ASSOC);

if (!$estadoEnProceso) {
    echo json_encode(['success' => false, 'message' => 'Estado "en_proceso" no definido para producción']);
    exit;
}
$estado_id_en_proceso = $estadoEnProceso['id'];

// Verificar que la producción esté en estado "en_proceso"
$stmtProduccion = $pdo->prepare("SELECT estado_id FROM producciones WHERE id = :id LIMIT 1");
$stmtProduccion->execute([':id' => $produccion_id]);
$produccion = $stmtProduccion->fetch(PDO::FETCH_ASSOC);

if (!$produccion) {
    echo json_encode(['success' => false, 'message' => 'Producción no encontrada']);
    exit;
}

if ((int)$produccion['estado_id'] !== (int)$estado_id_en_proceso) {
    echo json_encode(['success' => false, 'message' => 'No se puede asignar una tarea. La producción no está en estado "en_proceso"']);
    exit;
}

// Insertar la tarea con estado "pendiente"
$sql = "INSERT INTO tareas_produccion (produccion_id, descripcion, responsable_id, estado_id, fecha_inicio, fecha_fin)
        VALUES (:produccion_id, :descripcion, :responsable_id, :estado_id, :fecha_inicio, :fecha_fin)";
$stmt = $pdo->prepare($sql);
$exito = $stmt->execute([
    ':produccion_id' => $produccion_id,
    ':descripcion' => $descripcion,
    ':responsable_id' => $responsable_id,
    ':estado_id' => $estado_id_pendiente,
    ':fecha_inicio' => $fecha_inicio,
    ':fecha_fin' => $fecha_fin
]);

echo json_encode([
    'success' => $exito,
    'message' => $exito ? 'Tarea registrada con estado pendiente' : 'Error al registrar la tarea'
]);
