<?php
header('Content-Type: application/json');
require '../config/conexion.php';

// Respuesta final
$response = [
  'ventas_mes' => 0,
  'pedidos_activos' => 0,
  'pedidos_proximos_vencer' => 0,
  'producciones' => 0,
  'producciones_promedio' => 0,
  'alertas_stock' => 0,
  'producciones_activas' => [],
  'rendimiento_equipo' => [],
  'resumen_financiero' => [],
  'clientes_mes' => [],
];

// 🟢 1. Ventas del mes actual
$mes_actual = date('Y-m');
$mes_anterior = date('Y-m', strtotime('-1 month'));

// Total ventas mes actual
$sql = "SELECT SUM(total) FROM ventas WHERE DATE_FORMAT(fecha, '%Y-%m') = :mes";
$stmt = $pdo->prepare($sql);
$stmt->execute(['mes' => $mes_actual]);
$total_mes_actual = floatval($stmt->fetchColumn() ?? 0);

// Total ventas mes anterior
$stmt = $pdo->prepare($sql); // reutilizamos el mismo SQL
$stmt->execute(['mes' => $mes_anterior]);
$total_mes_anterior = floatval($stmt->fetchColumn() ?? 0);

// Calcular porcentaje de variación
if ($total_mes_anterior > 0) {
  $variacion = (($total_mes_actual - $total_mes_anterior) / $total_mes_anterior) * 100;
} else {
  $variacion = 0; // o 0 si prefieres evitar mostrar "infinito"
}

$response['ventas_mes'] = number_format($total_mes_actual, 2, '.', '');
$response['variacion_ventas'] = round($variacion, 1);

// 🟠 2. Pedidos activos
$sql = "SELECT COUNT(*) FROM pedidos WHERE estado_id != (SELECT id FROM estados WHERE nombre = 'entregado' AND entidad = 'pedido')";
$response['pedidos_activos'] = intval($pdo->query($sql)->fetchColumn());

// 2.1 Próximos a vencer (en 3 días o menos)
$sql = "
SELECT COUNT(*) FROM pedidos p
JOIN producciones pr ON pr.solicitud_id = p.id
WHERE p.estado_id != (
    SELECT id FROM estados 
    WHERE nombre = 'finalizado' AND entidad = 'pedido'
)
AND DATEDIFF(DATE_ADD(pr.fecha_inicio, INTERVAL p.fecha_entrega DAY), CURDATE()) <= 3
";
$response['pedidos_proximos_vencer'] = intval($pdo->query($sql)->fetchColumn());



// 🔵 3. Producciones en curso
// Obtener el ID del estado "en_proceso" para producciones
$sql = "SELECT id FROM estados WHERE nombre = 'en_proceso' AND entidad = 'produccion'";
$estadoProduccionId = $pdo->query($sql)->fetchColumn();
$sql = "SELECT id FROM estados WHERE nombre = 'finalizado' AND entidad = 'produccion'";
$estadoFinalizadoProduccionId = $pdo->query($sql)->fetchColumn();

// Contar producciones en curso (con ese estado)
$sql = "SELECT COUNT(*) FROM producciones WHERE estado_id = :estado_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['estado_id' => $estadoProduccionId]);
$response['producciones'] = intval($stmt->fetchColumn());

// Contar producciones finalizadas (con ese estado)
$sql = "SELECT COUNT(*) FROM producciones WHERE estado_id = :estado_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['estado_id' => $estadoFinalizadoProduccionId]);
$response['producciones_promedio'] = intval($stmt->fetchColumn());



// 🔵 3.1. Producciones activas con detalles
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
LIMIT 6
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['estado_proceso' => $estadoProduccionId]);
$produccionesActivas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$response['producciones_activas'] = $produccionesActivas;


$sql = "
SELECT 
  e.id AS empleado_id,
  CONCAT(e.nombre, ' ', e.apellido) AS nombre_completo,
  u.imagen,
  r.nombre AS rol,
  COUNT(tp.id) AS total_tareas_asignadas,

  -- Rendimiento ponderado según el estado
  ROUND(
    (SUM(
      CASE 
        WHEN est_t.nombre = 'pendiente' THEN 0
        WHEN est_t.nombre = 'en_progreso' THEN 0.5
        WHEN est_t.nombre = 'completado' THEN 1
        ELSE 0
      END
    ) / COUNT(tp.id)) * 100,
    2
  ) AS rendimiento,

  -- Avance de producción relacionado (suma de porcentajes por producción)
  COALESCE((
    SELECT SUM(ap.porcentaje)
    FROM avances_produccion ap
    WHERE ap.produccion_id = tp.produccion_id
  ), 0) AS produccion

FROM tareas_produccion tp
JOIN producciones p ON tp.produccion_id = p.id
JOIN estados est_p ON est_p.id = p.estado_id
  AND est_p.entidad = 'produccion' AND est_p.nombre = 'en_proceso'
JOIN estados est_t ON est_t.id = tp.estado_id
JOIN empleados e ON tp.responsable_id = e.id
JOIN usuarios u ON u.empleado_id = e.id
JOIN roles r ON r.id = u.rol_id
GROUP BY e.id, u.imagen, r.nombre, e.nombre, e.apellido
ORDER BY nombre_completo;

";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$rendimiento_equipo = $stmt->fetchAll(PDO::FETCH_ASSOC);
$response['rendimiento_equipo'] = $rendimiento_equipo;


// 🟣 4. Materiales con stock bajo
$sql = "SELECT COUNT(*) FROM materiales WHERE stock_actual < stock_minimo";
$response['alertas_stock'] = intval($pdo->query($sql)->fetchColumn());


/* 4. resumen financiero */

$stmt = $pdo->query("
SELECT
-- Ingresos del mes
  (SELECT IFNULL(SUM(v.total), 0)
   FROM ventas v
   WHERE MONTH(v.fecha) = MONTH(CURRENT_DATE())
   AND YEAR(v.fecha) = YEAR(CURRENT_DATE())) AS ingresos_mes,

  -- Gastos del mes
  (SELECT IFNULL(SUM(c.total), 0)
  FROM compras c
  WHERE MONTH(c.fecha) = MONTH(CURRENT_DATE())
  AND YEAR(c.fecha) = YEAR(CURRENT_DATE())) AS gastos_mes,

  -- Ganancia
  ((SELECT IFNULL(SUM(v.total), 0)
  FROM ventas v
  WHERE MONTH(v.fecha) = MONTH(CURRENT_DATE())
  AND YEAR(v.fecha) = YEAR(CURRENT_DATE()))
  -
  (SELECT IFNULL(SUM(c.total), 0)
  FROM compras c
  WHERE MONTH(c.fecha) = MONTH(CURRENT_DATE())
  AND YEAR(c.fecha) = YEAR(CURRENT_DATE()))
  ) AS ganancia,
  
  -- Facturas pagadas
  (SELECT IFNULL(SUM(p.monto_pagado), 0)
   FROM pagos p
   JOIN facturas f ON p.factura_id = f.id
   WHERE MONTH(p.fecha_pago) = MONTH(CURRENT_DATE())
     AND YEAR(p.fecha_pago) = YEAR(CURRENT_DATE())) AS facturas_pagadas,

  -- Facturas pendientes
  (SELECT IFNULL(SUM(f.saldo_pendiente), 0)
   FROM facturas f
   JOIN estados es ON es.id = f.estado_id
   WHERE es.entidad = 'factura' AND es.nombre = 'pendiente'
     AND MONTH(f.fecha_emision) = MONTH(CURRENT_DATE())
     AND YEAR(f.fecha_emision) = YEAR(CURRENT_DATE())) AS facturas_pendientes;
     
     ");
$resumen = $stmt->fetch(PDO::FETCH_ASSOC);
$response['resumen_financiero'] = $resumen;


/*  clientes del mes -- */
$stmt = $pdo->query("
SELECT
  c.id AS cliente_id,
  c.nombre AS nombre_cliente,
  COUNT(v.id) AS cantidad_ventas,
  COALESCE(SUM(v.total), 0) AS total_gastado
FROM clientes c
INNER JOIN ventas v ON v.cliente_id = c.id
WHERE YEAR(v.fecha) = YEAR(CURDATE())
  AND MONTH(v.fecha) = MONTH(CURDATE())
GROUP BY c.id, c.nombre
ORDER BY total_gastado DESC
LIMIT 5;
     ");

$cliente = $stmt->fetchAll(PDO::FETCH_ASSOC);
$response['clientes_mes'] = $cliente;


// Devolver JSON
echo json_encode($response);
