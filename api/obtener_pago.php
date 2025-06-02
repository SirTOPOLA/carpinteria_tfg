<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../config/conexion.php';
header('Content-Type: application/json');

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'mensaje' => 'ID de pago inválido']);
    exit;
}

$pago_id = intval($_POST['id']);

try {
    // Obtener datos de configuración
    $stmtConfig = $pdo->query("SELECT * FROM configuracion LIMIT 1");
    $config = $stmtConfig->fetch(PDO::FETCH_ASSOC);

    // Obtener información completa del pago
    $stmt = $pdo->prepare("
        SELECT 
            p.id,
            p.factura_id,
            p.monto_pagado,
            p.fecha_pago,
            p.metodo_pago,
            p.observaciones,

            f.fecha_emision,
            f.monto_total,
            f.saldo_pendiente,
            e.nombre AS estado_factura,

            v.id AS id_venta,
            COALESCE(c.nombre, v.nombre_cliente) AS nombre_cliente,
            COALESCE(c.direccion, v.direccion_cliente) AS direccion_cliente,
            COALESCE(c.telefono, '') AS cliente_telefono,
            COALESCE(c.email, '') AS cliente_email

        FROM pagos p
        JOIN facturas f ON p.factura_id = f.id
        JOIN estados e ON f.estado_id = e.id
        JOIN ventas v ON f.venta_id = v.id
        LEFT JOIN clientes c ON v.cliente_id = c.id
        WHERE p.id = :pago_id
    ");

    $stmt->execute([':pago_id' => $pago_id]);
    $pago = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pago) {
        echo json_encode(['success' => false, 'mensaje' => 'Pago no encontrado']);
        exit;
    }

    // Calcular el total acumulado de pagos anteriores e incluyendo el actual
    $stmtAcumulado = $pdo->prepare("
        SELECT SUM(monto_pagado) AS total_pagado
        FROM pagos
        WHERE factura_id = :factura_id AND id <= :pago_id
    ");
    $stmtAcumulado->execute([
        ':factura_id' => $pago['factura_id'],
        ':pago_id' => $pago_id
    ]);
    $row = $stmtAcumulado->fetch(PDO::FETCH_ASSOC);
    $total_pagado_acumulado = $row['total_pagado'] ?? 0;

    echo json_encode([
        'success' => true,
        'pago' => $pago,
        'config' => $config,
        'acumulado' => $total_pagado_acumulado
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'mensaje' => 'Error en la base de datos']);
}
