<?php

header('Content-Type: application/json');
require_once '../config/conexion.php';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar inputs básicos
    $cliente_id = filter_input(INPUT_POST, 'responsable_id', FILTER_VALIDATE_INT);
    $proyecto = trim($_POST['proyecto'] ?? '');
    $servicio_id = filter_input(INPUT_POST, 'servicio_id', FILTER_VALIDATE_INT);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $mano_obra = filter_input(INPUT_POST, 'mano_obra', FILTER_VALIDATE_FLOAT);
    $total = filter_input(INPUT_POST, 'total', FILTER_VALIDATE_FLOAT);
    $estado_texto = trim($_POST['estado'] ?? 'cotizado'); // viene 'cotizado' o similar

    $material_ids = $_POST['material_id'] ?? [];
    $cantidades = $_POST['cantidad'] ?? [];

    // Validaciones mínimas
    if (!$cliente_id) {
        die("Cliente no válido");
    }
    if ($mano_obra === false) $mano_obra = 0;
    if ($total === false) $total = 0;

    // Buscar el ID del estado en tabla estados
    $stmtEstado = $pdo->prepare("SELECT id FROM estados WHERE nombre = ? AND entidad = 'cotizado' LIMIT 1");
    $stmtEstado->execute(['nombre' => $estado_texto]);
    $estado = $stmtEstado->fetchColumn();
    if (!$estado) {
        die("Estado no encontrado");
    }

    // Fechas: hoy y +7 días
    $fecha_solicitud = date('Y-m-d');
    $fecha_entrega = (int) ($_POST['fecha_entrega']);

    try {
        $pdo->beginTransaction();

        // Insertar pedido
        $stmtPedido = $pdo->prepare("INSERT INTO pedidos (cliente_id, proyecto, servicio_id, descripcion, fecha_solicitud, fecha_entrega, precio_obra, estimacion_total, estado_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtPedido->execute([
            $cliente_id,
            $proyecto,
            $servicio_id ?: null,
            $descripcion,
            $fecha_solicitud,
            $fecha_entrega,
            $mano_obra,
            $total,
            $estado
        ]);

        $pedido_id = $pdo->lastInsertId();

        // Insertar detalles materiales
        $stmtDetalle = $pdo->prepare("INSERT INTO detalles_pedido_material (pedido_id, material_id, cantidad) VALUES (?, ?, ?)");

        for ($i = 0; $i < count($material_ids); $i++) {
            $mat_id = filter_var($material_ids[$i], FILTER_VALIDATE_INT);
            $cant = filter_var($cantidades[$i], FILTER_VALIDATE_FLOAT);
            if ($mat_id && $cant && $cant > 0) {
                $stmtDetalle->execute([$pedido_id, $mat_id, $cant]);
            }
        }

        $pdo->commit();

        echo json_encode(['status' => true, 'message' => 'Pedido registrado correctamente.']);

    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['status' => false, 'message' => 'Error al guardar el pedido: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => false, 'message' => 'Método no permitido']);
}

