<?php
header('Content-Type: application/json');

require '../config/conexion.php';

// Manejo global de errores
set_exception_handler(function ($e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'mensaje' => 'Error en el servidor.',
        'detalle' => $e->getMessage()
    ]);
    exit;
});

$response = [
    'ventas_mes' => 0,
    'variacion_ventas' => 0,
    'pedidos_activos' => 0,
    'pedidos_proximos_vencer' => 0,
    'producciones' => 0,
    'producciones_promedio' => 0,
    'alertas_stock' => 0,
    'producciones_activas' => [],
    'rendimiento_equipo' => [],
    'resumen_financiero' => [],
    'clientes_mes' => [],
    'beneficios_produccion' => [],
];

try {
    // 🟢 Ventas
    $mes_actual = date('Y-m');
    $mes_anterior = date('Y-m', strtotime('-1 month'));

    $sql = "SELECT SUM(total) FROM ventas WHERE DATE_FORMAT(fecha, '%Y-%m') = :mes";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['mes' => $mes_actual]);
    $total_mes_actual = floatval($stmt->fetchColumn() ?? 0);

    $stmt->execute(['mes' => $mes_anterior]);
    $total_mes_anterior = floatval($stmt->fetchColumn() ?? 0);

    $variacion = $total_mes_anterior > 0 ? (($total_mes_actual - $total_mes_anterior) / $total_mes_anterior) * 100 : 0;

    $response['ventas_mes'] = number_format($total_mes_actual, 2, '.', '');
    $response['variacion_ventas'] = round($variacion, 1);

    // 🟠 Pedidos activos y próximos a vencer
    $sql = "SELECT COUNT(*) FROM pedidos WHERE estado_id != (SELECT id FROM estados WHERE nombre = 'entregado' AND entidad = 'pedido')";
    $response['pedidos_activos'] = intval($pdo->query($sql)->fetchColumn());

    $sql = "
    SELECT COUNT(*) FROM pedidos p
    JOIN producciones pr ON pr.solicitud_id = p.id
    WHERE p.estado_id != (
        SELECT id FROM estados WHERE nombre = 'finalizado' AND entidad = 'pedido'
    )
    AND DATEDIFF(DATE_ADD(pr.fecha_inicio, INTERVAL p.fecha_entrega DAY), CURDATE()) <= 3";
    $response['pedidos_proximos_vencer'] = intval($pdo->query($sql)->fetchColumn());

    // 🔵 Producciones
    $estadoProduccionId = $pdo->query("SELECT id FROM estados WHERE nombre = 'en_proceso' AND entidad = 'produccion'")->fetchColumn();
    $estadoFinalizadoProduccionId = $pdo->query("SELECT id FROM estados WHERE nombre = 'finalizado' AND entidad = 'produccion'")->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM producciones WHERE estado_id = :estado_id");
    $stmt->execute(['estado_id' => $estadoProduccionId]);
    $response['producciones'] = intval($stmt->fetchColumn());

    $stmt->execute(['estado_id' => $estadoFinalizadoProduccionId]);
    $response['producciones_promedio'] = intval($stmt->fetchColumn());

    // 🔵 Producciones activas con detalles
    $sql = "
    SELECT 
      pr.id AS produccion_id,
      p.descripcion AS pedido_desc,
      e.nombre AS responsable_nombre,
      MAX(a.porcentaje) AS porcentaje,
      a.descripcion AS avance_desc
    FROM producciones pr
    LEFT JOIN pedidos p ON pr.solicitud_id = p.id
    LEFT JOIN empleados e ON pr.responsable_id = e.id
    LEFT JOIN avances_produccion a ON a.produccion_id = pr.id
    WHERE pr.estado_id = :estado_proceso
    GROUP BY pr.id
    ORDER BY porcentaje DESC
    LIMIT 6";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['estado_proceso' => $estadoProduccionId]);
    $response['producciones_activas'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 🔵 Rendimiento equipo
    $stmt = $pdo->query("
    SELECT 
      e.id AS empleado_id,
      CONCAT(e.nombre, ' ', e.apellido) AS nombre_completo,
      u.imagen,
      r.nombre AS rol,
      COUNT(tp.id) AS total_tareas_asignadas,
      ROUND((SUM(CASE 
        WHEN est_t.nombre = 'pendiente' THEN 0
        WHEN est_t.nombre = 'en_progreso' THEN 0.5
        WHEN est_t.nombre = 'completado' THEN 1
        ELSE 0
      END) / COUNT(tp.id)) * 100, 2) AS rendimiento,
      COALESCE((SELECT SUM(ap.porcentaje) FROM avances_produccion ap WHERE ap.produccion_id = tp.produccion_id), 0) AS produccion
    FROM tareas_produccion tp
    JOIN producciones p ON tp.produccion_id = p.id
    JOIN estados est_p ON est_p.id = p.estado_id AND est_p.entidad = 'produccion' AND est_p.nombre = 'en_proceso'
    JOIN estados est_t ON est_t.id = tp.estado_id
    JOIN empleados e ON tp.responsable_id = e.id
    JOIN usuarios u ON u.empleado_id = e.id
    JOIN roles r ON r.id = u.rol_id
    GROUP BY e.id, u.imagen, r.nombre, e.nombre, e.apellido
    ORDER BY nombre_completo
    ");
    $response['rendimiento_equipo'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 🟣 Materiales bajo stock
    $response['alertas_stock'] = intval($pdo->query("SELECT COUNT(*) FROM materiales WHERE stock_actual < stock_minimo")->fetchColumn());

    // 🟡 Resumen financiero
    $stmt = $pdo->query("
    SELECT
      (SELECT IFNULL(SUM(p.monto_pagado), 0) FROM pagos p WHERE MONTH(p.fecha_pago) = MONTH(CURRENT_DATE()) AND YEAR(p.fecha_pago) = YEAR(CURRENT_DATE())) AS ingresos_mes,
      (SELECT IFNULL(SUM(c.total), 0) FROM compras c WHERE MONTH(c.fecha) = MONTH(CURRENT_DATE()) AND YEAR(c.fecha) = YEAR(CURRENT_DATE())) AS gastos_mes,
      ((SELECT IFNULL(SUM(v.total), 0) FROM ventas v WHERE MONTH(v.fecha) = MONTH(CURRENT_DATE()) AND YEAR(v.fecha) = YEAR(CURRENT_DATE()))
        -
       (SELECT IFNULL(SUM(c.total), 0) FROM compras c WHERE MONTH(c.fecha) = MONTH(CURRENT_DATE()) AND YEAR(c.fecha) = YEAR(CURRENT_DATE()))
      ) AS ganancia,
      (SELECT IFNULL(SUM(p.monto_pagado), 0) FROM pagos p JOIN facturas f ON p.factura_id = f.id WHERE MONTH(p.fecha_pago) = MONTH(CURRENT_DATE()) AND YEAR(p.fecha_pago) = YEAR(CURRENT_DATE())) AS facturas_pagadas,
      (SELECT IFNULL(SUM(f.saldo_pendiente), 0) FROM facturas f JOIN estados es ON es.id = f.estado_id WHERE es.entidad = 'factura' AND es.nombre = 'pendiente' AND MONTH(f.fecha_emision) = MONTH(CURRENT_DATE()) AND YEAR(f.fecha_emision) = YEAR(CURRENT_DATE())) AS facturas_pendientes
    ");
    $response['resumen_financiero'] = $stmt->fetch(PDO::FETCH_ASSOC);

    // 🟤 Clientes del mes
    $stmt = $pdo->query("
    SELECT
      c.id AS cliente_id,
      c.nombre AS nombre_cliente,
      COUNT(v.id) AS cantidad_ventas,
      COALESCE(SUM(v.total), 0) AS total_gastado
    FROM clientes c
    INNER JOIN ventas v ON v.cliente_id = c.id
    WHERE YEAR(v.fecha) = YEAR(CURDATE()) AND MONTH(v.fecha) = MONTH(CURDATE())
    GROUP BY c.id, c.nombre
    ORDER BY total_gastado DESC
    LIMIT 5
    ");
    $response['clientes_mes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 🟤 Beneficios por producción
    $stmt = $pdo->query("
    SELECT 
      p.id AS produccion_id,
      ped.id AS pedido_id,
      ped.proyecto,
      p.fecha_inicio,
      ped.estimacion_total,
      IFNULL(SUM(dm.cantidad * dc.precio_unitario), 0) AS costo_materiales,
      (ped.estimacion_total - IFNULL(SUM(dm.cantidad * dc.precio_unitario), 0)) AS beneficio_estimado
    FROM producciones p
    JOIN pedidos ped ON p.solicitud_id = ped.id
    LEFT JOIN movimientos_material dm ON dm.produccion_id = p.id AND dm.tipo_movimiento = 'salida'
    LEFT JOIN detalles_compra dc ON dc.material_id = dm.material_id
    GROUP BY p.id, ped.id, ped.proyecto, ped.estimacion_total
    ORDER BY p.id DESC
    ");
    $response['beneficios_produccion'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ Enviar respuesta
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'mensaje' => 'Error en servidor',
        'detalle' => $e->getMessage()
    ]);
}
