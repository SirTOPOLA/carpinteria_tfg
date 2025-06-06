<?php

header('Content-Type: application/json');
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar inputs
    $cliente_id = filter_input(INPUT_POST, 'responsable_id', FILTER_VALIDATE_INT);
    $servicio_id = filter_input(INPUT_POST, 'servicio_id', FILTER_VALIDATE_INT);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $mano_obra = filter_input(INPUT_POST, 'mano_obra', FILTER_VALIDATE_FLOAT);
    $total = trim($_POST['total']);
    $estado_texto = trim($_POST['estado'] ?? 'cotizado');
    $fecha_entrega = $_POST['fecha_entrega'] ?? null;

    $tipo_producto = $_POST['tipo_producto'] ?? '';
    $producto_id = filter_input(INPUT_POST, 'producto_id', FILTER_VALIDATE_INT);
    $proyecto = trim($_POST['proyecto'] ?? '');
    $cantidad_producto = filter_input(INPUT_POST, 'cantidad_producto', FILTER_VALIDATE_INT);

    $material_ids = $_POST['material_id'] ?? [];
    $cantidades = $_POST['cantidad'] ?? [];

    // Validaciones básicas
    if (!$cliente_id) {
        echo json_encode(['status' => false, 'message' => 'Cliente no válido']);
        exit;
    }

    if ($mano_obra === false) $mano_obra = 0;
    if ($total === false) $total = 0;
    if (!$cantidad_producto || $cantidad_producto < 1) $cantidad_producto = 1;

    // Obtener estado
    $stmtEstado = $pdo->prepare("SELECT id FROM estados WHERE nombre = ? AND entidad = 'pedido' LIMIT 1");
    $stmtEstado->execute([$estado_texto]);
    $estado = $stmtEstado->fetchColumn();

    if (!$estado) {
        echo json_encode(['status' => false, 'message' => 'Estado no encontrado']);
        exit;
    }

    // Validar tipo de producto
    if ($tipo_producto === 'existente') {
        if (!$producto_id) {
            echo json_encode(['status' => false, 'message' => 'Debe seleccionar un producto existente']);
            exit;
        }

        // Buscar nombre del producto
        $stmtNombre = $pdo->prepare("SELECT nombre FROM productos WHERE id = ? LIMIT 1");
        $stmtNombre->execute([$producto_id]);
        $nombre_producto = $stmtNombre->fetchColumn();

        if (!$nombre_producto) {
            echo json_encode(['status' => false, 'message' => 'Producto no encontrado']);
            exit;
        }

        // Sobrescribir el campo proyecto con el nombre del producto
        $proyecto = $nombre_producto;
    }

    if ($tipo_producto === 'nuevo' && $proyecto === '') {
        echo json_encode(['status' => false, 'message' => 'Debe ingresar el nombre del proyecto']);
        exit;
    }

    $fecha_solicitud = date('Y-m-d');

    try {
        $pdo->beginTransaction();

        // Insertar pedido
        $stmtPedido = $pdo->prepare("INSERT INTO pedidos (cliente_id, proyecto, servicio_id, descripcion, fecha_solicitud, fecha_entrega, precio_obra, estimacion_total, estado_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtPedido->execute([
            $cliente_id,
            $proyecto ?: null,
            $servicio_id ?: null,
            $descripcion,
            $fecha_solicitud,
            $fecha_entrega,
            $mano_obra,
            $total,
            $estado
        ]);

        $pedido_id = $pdo->lastInsertId();

        // Insertar materiales
        $stmtDetalleMaterial = $pdo->prepare("INSERT INTO detalles_pedido_material (pedido_id, material_id, cantidad) VALUES (?, ?, ?)");
        for ($i = 0; $i < count($material_ids); $i++) {
            $mat_id = filter_var($material_ids[$i], FILTER_VALIDATE_INT);
            $cant = filter_var($cantidades[$i], FILTER_VALIDATE_FLOAT);
            if ($mat_id && $cant && $cant > 0) {
                $stmtDetalleMaterial->execute([$pedido_id, $mat_id, $cant]);
            }
        }

       /*  // Relación con producto existente
        if ($tipo_producto === 'existente' && $producto_id) {
            $stmtProducto = $pdo->prepare("INSERT INTO detalles_pedido_material (pedido_id, producto_id, cantidad) VALUES (?, ?, ?)");
            $stmtProducto->execute([$pedido_id, $producto_id, $cantidad_producto]);
        } */

        $pdo->commit();

        echo json_encode(['status' => true, 'message' => 'Pedido registrado correctamente.']);
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['status' => false, 'message' => 'Error al guardar el pedido: ' . $e->getMessage()]);
        exit;
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => false, 'message' => 'Método no permitido']);
    exit;
}
