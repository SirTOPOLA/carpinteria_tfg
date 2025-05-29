<?php
header('Content-Type: application/json');
require_once '../config/conexion.php';

try { 
    $pdo->beginTransaction();

    // CLIENTE 
    $cliente_id = $_POST['responsable_id'];
    $fecha_solicitud = date('Y-m-d');
    $estado = $_POST['estado'] ?? 'cotizado';
    $fecha_inicio = $_POST['fecha_inicio'];

    // Este es el campo nuevo: precio_obra (mano de obra)
    $precio_obra = isset($_POST['mano_obra']) ? $_POST['mano_obra'] : 0;

    $total = isset($_POST['total']) ? $_POST['total'] : 0;

    $descripcion = $_POST['descripcion'] ?? 'Cotización generada automáticamente';

    // PROYECTO: crear nuevo si aplica
    if ($_POST['opcion'] === 'f') {
        $nombre_proyecto = $_POST['nombre'];
        $stmt = $pdo->prepare("INSERT INTO proyectos (nombre, estado, fecha_inicio) VALUES (?, 'en diseño', ?)");
        $stmt->execute([$nombre_proyecto, $fecha_inicio]);
        $proyecto_id = $pdo->lastInsertId();
    } else {
        $proyecto_id = $_POST['proyecto_id'];
    }

    // SERVICIO (opcional)
    $servicio_id = !empty($_POST['servicio_id']) ? trim($_POST['servicio_id'] ) : null;
    $coste_servicio = !empty($_POST['coste_servicio']) ? $_POST['coste_servicio'] : 0;

    // Registrar solicitud (cotización)
    $stmt = $pdo->prepare("INSERT INTO solicitudes_proyecto 
        (cliente_id, proyecto_id, descripcion, fecha_solicitud, estado, estimacion_total, servicio_id, precio_obra) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $cliente_id,
        $proyecto_id,
        $descripcion,
        $fecha_solicitud,
        $estado,
        0, // temporal, se actualizará luego
        $servicio_id,
        $precio_obra
    ]);
    $solicitud_id = $pdo->lastInsertId();

    // Registrar materiales
    $materiales = $_POST['material_id'];
    $cantidades = $_POST['cantidad'];
    $precios = $_POST['precio_unitario'];
    $subtotal_total = 0;

    $stmtMaterial = $pdo->prepare("INSERT INTO detalles_solicitud_material 
        (solicitud_id, material_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");

    for ($i = 0; $i < count($materiales); $i++) {
        if (!empty($materiales[$i]) && $cantidades[$i] > 0) {
            $stmtMaterial->execute([
                $solicitud_id,
                $materiales[$i],
                $cantidades[$i],
                $precios[$i]
            ]);
           // $subtotal_total += $cantidades[$i] * $precios[$i];
        }
    }

    // Actualizar estimación total
    $stmt = $pdo->prepare("UPDATE solicitudes_proyecto SET estimacion_total = ? WHERE id = ?");
    $stmt->execute([$total, $solicitud_id]);

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Cotización registrada correctamente.']);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al registrar cotización.', 'error' => $e->getMessage()]);
}
