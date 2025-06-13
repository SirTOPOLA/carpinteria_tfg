<?php 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

require '../config/conexion.php';
header('Content-Type: application/json');

$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de tarea inválido']);
    exit;
}

try {
    // Obtener el estado actual de la tarea
    $stmt = $pdo->prepare("SELECT estado_id FROM tareas_produccion WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $tarea = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tarea) {
        echo json_encode(['success' => false, 'message' => 'Tarea no encontrada']);
        exit;
    }

    $estadoActualId = (int) $tarea['estado_id'];

    // Obtener todos los estados de entidad = 'tareas'
    $stmtEstados = $pdo->prepare("SELECT id, nombre FROM estados WHERE entidad = 'tareas'");
    $stmtEstados->execute();
    $resultados = $stmtEstados->fetchAll(PDO::FETCH_ASSOC);

    $nombreToId = [];
    foreach ($resultados as $estado) {
        $nombre = $estado['nombre'];
        $estadoId = $estado['id'];
        $nombreToId[$nombre] = $estadoId;
    }

    // Verificar que existan los tres estados clave
    if (!isset($nombreToId['pendiente'], $nombreToId['en_progreso'], $nombreToId['completado'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan estados requeridos (pendiente, en_progreso, completado)',
            'estados_encontrados' => array_keys($nombreToId)
        ]);
        exit;
    }

    // Lógica de cambio de estado
    if ($estadoActualId === $nombreToId['pendiente']) {
        $nuevoEstadoId = $nombreToId['en_progreso'];
        $nuevoEstadoNombre = 'en_progreso';
    } elseif ($estadoActualId === $nombreToId['en_progreso']) {
        $nuevoEstadoId = $nombreToId['completado'];
        $nuevoEstadoNombre = 'completado';
    } elseif ($estadoActualId === $nombreToId['completado']) {
        echo json_encode(['success' => false, 'message' => 'La tarea ya está completada.']);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Estado actual no válido.']);
        exit;
    }

    // Actualizar el estado de la tarea
    $update = $pdo->prepare("UPDATE tareas_produccion SET estado_id = ? WHERE id = ?");
    $update->execute([$nuevoEstadoId, $id]);

    echo json_encode([
        'success' => true,
        'message' => 'Estado actualizado',
        'nuevo_estado' => $nuevoEstadoNombre
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor', 'error' => $e->getMessage()]);
}
