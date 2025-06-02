<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../config/conexion.php';
header('Content-Type: application/json');

// Validar el parámetro recibido
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'mensaje' => 'ID de venta inválido']);
    exit;
}

$venta_id = intval($_POST['id']);

try {
    $stmtConfig = $pdo->query("SELECT * FROM configuracion LIMIT 1");
    $config = $stmtConfig->fetch(PDO::FETCH_ASSOC);

    // Obtener datos generales de la venta
    $stmtVenta = $pdo->prepare("
        SELECT v.id, v.fecha, v.total, c.nombre AS cliente 
        FROM ventas v
        JOIN clientes c ON v.cliente_id = c.id 
        WHERE v.id = :venta_id
    ");
    $stmtVenta->execute([':venta_id' => $venta_id]);
    $venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);


    if (!$venta) {
        $stmtVenta = $pdo->prepare("  SELECT * FROM ventas    WHERE  id = :venta_id  ");
        $stmtVenta->execute([':venta_id' => $venta_id]);
        $venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);
       /*  echo json_encode(['success' => false, 'message' => 'Venta no encontrada']);
        exit; */
    }

    // Obtener detalles de los productos vendidos
    $stmtDetalles = $pdo->prepare("
        SELECT 
        dv.cantidad,
        dv.precio_unitario,
        dv.subtotal,
        dv.descuento,
        COALESCE(p.nombre, s.nombre) AS nombre,
        dv.tipo
    FROM detalles_venta dv
    LEFT JOIN productos p ON dv.producto_id = p.id
    LEFT JOIN servicios s ON dv.servicio_id = s.id
    WHERE dv.venta_id = :venta_id
    ");
    $stmtDetalles->execute([':venta_id' => $venta_id]);
    $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);


    echo json_encode([
        'success' => true,
        'venta' => $venta,
        'detalles' => $detalles,
        'config' => $config
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'mensaje' => 'Error de base de datos']);
}
