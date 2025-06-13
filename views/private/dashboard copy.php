<?php
if (session_status() === PHP_SESSION_NONE) session_start();
 

$rol       = $_SESSION['usuario']['rol']   ?? '';
$usuarioId = $_SESSION['usuario']['id']    ?? null;
$clienteId = $_SESSION['usuario']['id']    ?? null; // asumiendo que usuario.id = cliente_id para rol cliente

// ========== FUNCIONES ==========

// 1. Total de ventas
function totalVentas(PDO $pdo): float {
    $sql = "SELECT IFNULL(SUM(total),0) FROM ventas";
    return (float) $pdo->query($sql)->fetchColumn();
}

// 2. Total de compras
function totalCompras(PDO $pdo): float {
    $sql = "SELECT IFNULL(SUM(total),0) FROM compras";
    return (float) $pdo->query($sql)->fetchColumn();
}

// 3. Tareas del operario en el día
function tareasOperario(PDO $pdo, int $empleadoId): array {
    $sql = "
      SELECT 
        tp.id,
        tp.descripcion,
        e.nombre AS estado,
        tp.fecha_inicio
      FROM tareas_produccion tp
      INNER JOIN estados e 
        ON tp.estado_id = e.id AND e.entidad = 'tareas'
      WHERE tp.responsable_id = ? 
        AND DATE(tp.fecha_inicio) = CURDATE()
      ORDER BY tp.fecha_inicio DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$empleadoId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 4. Producciones asignadas al operario
function produccionesAsignadas(PDO $pdo, int $empleadoId): array {
    $sql = "
      SELECT 
        pr.id,
        e.nombre AS estado,
        pr.fecha_inicio,
        pr.fecha_fin
      FROM producciones pr
      INNER JOIN estados e 
        ON pr.estado_id = e.id AND e.entidad = 'produccion'
      WHERE pr.responsable_id = ?
      ORDER BY pr.created_at DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$empleadoId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 5. Pedidos de un cliente
function pedidosCliente(PDO $pdo, int $clienteId): array {
    $sql = "
      SELECT 
        p.proyecto,
        p.piezas,
        e.nombre AS estado,
        DATE_ADD(p.fecha_solicitud, INTERVAL p.fecha_entrega DAY) AS fecha_entrega_real
      FROM pedidos p
      LEFT JOIN estados e 
        ON p.estado_id = e.id AND e.entidad = 'pedido'
      WHERE p.cliente_id = ?
      ORDER BY p.fecha_solicitud DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$clienteId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ejemplo de uso:
if ($rol === 'administrador') {
    echo "Ventas: S/".totalVentas($pdo)."<br>";
    echo "Compras: S/".totalCompras($pdo)."<br>";
}

if ($rol === 'operario' && $usuarioId) {
    $tareas = tareasOperario($pdo, $usuarioId);
    // Pintar tareas...
}

if ($rol === 'cliente' && $clienteId) {
    $pedidos = pedidosCliente($pdo, $clienteId);
    // Pintar pedidos...
}


$labelsMateriales = [];
$cantidadesMateriales = [];

$sql = "SELECT m.nombre, SUM(dpm.cantidad) AS total 
        FROM detalles_pedido_material dpm 
        JOIN materiales m ON m.id = dpm.material_id 
        GROUP BY dpm.material_id 
        ORDER BY total DESC LIMIT 10";

$stmt = $pdo->query($sql);
while ($row = $stmt->fetch()) {
  $labelsMateriales[] = $row['nombre'];
  $cantidadesMateriales[] = $row['total'];
}

$labelsOperarios = [];
$dataTareas     = [];

$sql = "
  SELECT 
    emp.id AS empleado_id,
    CONCAT(emp.nombre, ' ', emp.apellido) AS operario,
    COUNT(t.id) AS total_tareas,
    SUM(CASE WHEN e.nombre = 'Finalizado' AND e.entidad = 'tareas' THEN 1 ELSE 0 END) AS completadas
  FROM tareas_produccion t
  INNER JOIN empleados emp 
    ON emp.id = t.responsable_id
  INNER JOIN estados e 
    ON e.id = t.estado_id
  GROUP BY emp.id, emp.nombre, emp.apellido
";

$stmt = $pdo->query($sql);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $labelsOperarios[] = $row['operario'];
    $total = (int)$row['total_tareas'];
    $done  = (int)$row['completadas'];
    $porcentaje = $total > 0
      ? round($done / $total * 100)
      : 0;
    $dataTareas[] = $porcentaje;
}
$hoy = date('Y-m-d');

$sql = "
SELECT 
  p.id,
  c.nombre    AS cliente,
  p.proyecto,
  p.fecha_solicitud,
  p.fecha_entrega      AS dias_plazo,
  DATE_ADD(p.fecha_solicitud, INTERVAL p.fecha_entrega DAY) AS fecha_entrega_real,
  e.nombre    AS estado
FROM pedidos p
INNER JOIN clientes c ON c.id     = p.cliente_id
INNER JOIN estados  e ON e.id     = p.estado_id
  AND e.entidad = 'pedido'
WHERE 
  DATE_ADD(p.fecha_solicitud, INTERVAL p.fecha_entrega DAY) < :hoy
  AND e.nombre != 'Finalizado'
ORDER BY fecha_entrega_real ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['hoy' => $hoy]);
$pedidosAtrasados = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<div id="content" class="container-fluid px-4">
  <div class="container mt-4">
    <h2 class="mb-4">Panel de Control</h2>

    <?php if ($rol === 'Administrador'): ?>
      <!-- Estilo de tarjetas contables -->
<div class="row g-4">
  <!-- Total Ventas -->
  <div class="col-md-4">
    <div class="card border-0 shadow-sm rounded-4 bg-light">
      <div class="card-body d-flex align-items-center gap-3">
        <i class="bi bi-currency-dollar display-6 text-success"></i>
        <div>
          <h6 class="text-muted mb-1">Total Ventas</h6>
          <h4 class="fw-bold text-success">S/ <?= number_format(totalVentas($pdo), 2) ?></h4>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Compras -->
  <div class="col-md-4">
    <div class="card border-0 shadow-sm rounded-4 bg-light">
      <div class="card-body d-flex align-items-center gap-3">
        <i class="bi bi-cart-check display-6 text-primary"></i>
        <div>
          <h6 class="text-muted mb-1">Total Compras</h6>
          <h4 class="fw-bold text-primary">S/ <?= number_format(totalCompras($pdo), 2) ?></h4>
        </div>
      </div>
    </div>
  </div>

  <!-- Balance Neto -->
  <div class="col-md-4">
    <div class="card border-0 shadow-sm rounded-4 bg-light">
      <div class="card-body d-flex align-items-center gap-3">
        <i class="bi bi-bar-chart-line display-6 text-dark"></i>
        <div>
          <h6 class="text-muted mb-1">Balance Neto</h6>
          <h4 class="fw-bold text-dark">
            S/ <?= number_format(totalVentas($pdo) - totalCompras($pdo), 2) ?>
          </h4>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Gráfico de balance -->
<div class="row mt-5">
  <div class="col-12">
    <div class="card shadow-sm rounded-4 border-0">
      <div class="card-header bg-white border-bottom-0">
        <h6 class="mb-0 text-secondary"><i class="bi bi-graph-up-arrow me-2"></i>Balance Mensual (Ventas vs Compras)</h6>
      </div>
      <div class="card-body">
        <canvas id="graficoBalance" height="100"></canvas>
      </div>
    </div>
  </div>
</div>


<!-- Google Charts Gantt -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="card shadow-sm border-0 rounded-4 mt-4">
  <div class="card-header bg-white border-bottom-0">
    <h6 class="mb-0 text-secondary"><i class="bi bi-kanban me-2"></i>Diagrama de Gantt - Producción</h6>
  </div>
  <div class="card-body">
    <div id="gantt_chart" style="height: 400px;"></div>
  </div>
</div>


<!-- materiales mas usados -->
<div class="card mt-4 shadow-sm border-0 rounded-4">
  <div class="card-header bg-white border-bottom-0">
    <h6 class="mb-0 text-secondary"><i class="bi bi-box-seam me-2"></i>Materiales Más Utilizados</h6>
  </div>
  <div class="card-body">
    <canvas id="materialesMasUsados" height="250"></canvas>
  </div>
</div>
<!-- avances genera'l -->
<div class="card mt-4 shadow-sm border-0 rounded-4">
  <div class="card-header bg-white border-bottom-0">
    <h6 class="mb-0 text-secondary"><i class="bi bi-speedometer2 me-2"></i>Avance General de Producción</h6>
  </div>
  <div class="card-body">
    <div class="progress rounded-pill" style="height: 25px;">
      <div class="progress-bar bg-success fw-bold" role="progressbar"
        style="width: <?= $avanceGlobal ?>%" aria-valuenow="<?= $avanceGlobal ?>"
        aria-valuemin="0" aria-valuemax="100">
        <?= $avanceGlobal ?>%
      </div>
    </div>
  </div>
</div>


<div class="card mt-4 shadow-sm border-0 rounded-4">
  <div class="card-header bg-white border-bottom-0">
    <h6 class="mb-0 text-secondary"><i class="bi bi-person-check me-2"></i>Avance de Tareas por Operario</h6>
  </div>
  <div class="card-body">
    <canvas id="avanceOperarios" height="250"></canvas>
  </div>
</div>
<div class="card mt-4 shadow-sm border-0 rounded-4">
  <div class="card-header bg-white border-bottom-0">
    <h6 class="mb-0 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Pedidos Atrasados</h6>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Proyecto</th>
            <th>Fecha Entrega</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($pedidosAtrasados) > 0): ?>
            <?php foreach ($pedidosAtrasados as $p): ?>
              <tr class="table-danger">
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['cliente']) ?></td>
                <td><?= htmlspecialchars($p['proyecto']) ?></td>
                <td><?= date('d/m/Y', strtotime($p['fecha_entrega'])) ?></td>
                <td><span class="badge bg-danger"><?= htmlspecialchars($p['estado']) ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center text-muted">Sin pedidos atrasados.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  const ctxOperarios = document.getElementById('avanceOperarios').getContext('2d');
  new Chart(ctxOperarios, {
    type: 'bar',
    data: {
      labels: <?= json_encode($labelsOperarios) ?>,
      datasets: [{
        label: '% Tareas Completadas',
        data: <?= json_encode($dataTareas) ?>,
        backgroundColor: 'rgba(54, 162, 235, 0.7)',
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: { enabled: true },
        legend: { display: false }
      },
      scales: {
        y: {
          beginAtZero: true,
          max: 100,
          title: { display: true, text: '%' }
        }
      }
    }
  });
</script>

<script>
  const ctxMateriales = document.getElementById('materialesMasUsados').getContext('2d');
  new Chart(ctxMateriales, {
    type: 'bar',
    data: {
      labels: <?= json_encode($labelsMateriales) ?>,
      datasets: [{
        label: 'Cantidad Usada',
        data: <?= json_encode($cantidadesMateriales) ?>,
        backgroundColor: 'rgba(255, 159, 64, 0.7)'
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false }
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>

<script type="text/javascript">
  google.charts.load('current', {'packages':['gantt']});
  google.charts.setOnLoadCallback(drawChart);

  function daysToMilliseconds(days) {
    return days * 24 * 60 * 60 * 1000;
  }

  function drawChart() {
    const data = new google.visualization.DataTable();
    data.addColumn('string', 'ID');
    data.addColumn('string', 'Tarea');
    data.addColumn('string', 'Recurso');
    data.addColumn('date', 'Inicio');
    data.addColumn('date', 'Fin');
    data.addColumn('number', 'Duración');
    data.addColumn('number', 'Porcentaje');
    data.addColumn('string', 'Dependencias');

    data.addRows([
      <?php
      $stmt = $pdo->query("SELECT p.id, pe.proyecto, p.fecha_inicio, p.fecha_fin, COALESCE(SUM(a.porcentaje)/COUNT(a.id), 0) AS progreso
        FROM producciones p
        JOIN pedidos pe ON pe.id = p.solicitud_id
        LEFT JOIN avances_produccion a ON a.produccion_id = p.id
        GROUP BY p.id, pe.proyecto");
      while ($row = $stmt->fetch()) {
        echo "[
          'P{$row['id']}',
          '" . htmlspecialchars($row['proyecto']) . "',
          null,
          new Date('" . $row['fecha_inicio'] . "'),
          new Date('" . $row['fecha_fin'] . "'),
          null,
          " . round($row['progreso']) . ",
          null
        ],";
      }
      ?>
    ]);

    const options = {
      height: 400,
      gantt: {
        trackHeight: 35,
        barCornerRadius: 5,
        labelStyle: { fontName: "Roboto", fontSize: 12 }
      }
    };

    const chart = new google.visualization.Gantt(document.getElementById('gantt_chart'));
    chart.draw(data, options);
  }
</script>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Script del gráfico -->
<script>
  (function(){
    const ctx = document.getElementById('graficoBalance').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?= json_encode($meses) ?>,
        datasets: [
          {
            label: 'Ventas (S/)',
            data: <?= json_encode($datosVentas) ?>,
            backgroundColor: 'rgba(25, 135, 84, 0.7)'
          },
          {
            label: 'Compras (S/)',
            data: <?= json_encode($datosCompras) ?>,
            backgroundColor: 'rgba(13, 110, 253, 0.7)'
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              color: '#333',
              font: { weight: 'bold' }
            }
          },
          title: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              color: '#555'
            }
          },
          x: {
            ticks: {
              color: '#555'
            }
          }
        }
      }
    });
  })();
</script>


    <?php elseif ($rol === 'Operario'): ?>
      <div class="row g-4">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header bg-primary text-white">Tareas de Hoy</div>
            <ul class="list-group list-group-flush">
              <?php foreach (tareasOperario($pdo, $usuarioId) as $t): ?>
                <li class="list-group-item">
                  <strong><?= htmlspecialchars($t['descripcion']) ?></strong><br>
                  Estado: <?= htmlspecialchars($t['estado']) ?> <small class="text-muted">(<?= $t['fecha_inicio'] ?>)</small>
                </li>
              <?php endforeach; ?>
              <?php if (empty(tareasOperario($pdo, $usuarioId))): ?>
                <li class="list-group-item text-muted">No hay tareas para hoy.</li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-header bg-secondary text-white">Producciones Asignadas</div>
            <ul class="list-group list-group-flush">
              <?php foreach (produccionesAsignadas($pdo, $usuarioId) as $p): ?>
                <li class="list-group-item">
                  Producción #<?= $p['id'] ?> – <?= htmlspecialchars($p['estado']) ?>
                  <?php if ($p['fecha_fin']): ?>
                    <span class="badge bg-success float-end">Finalizada</span>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
              <?php if (empty(produccionesAsignadas($pdo, $usuarioId))): ?>
                <li class="list-group-item text-muted">No tienes producciones asignadas.</li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="card mt-4 shadow-sm border-0 rounded-4">
  <div class="card-header bg-white border-bottom-0">
    <h6 class="mb-0 text-secondary"><i class="bi bi-person-check me-2"></i>Avance de Tareas por Operario</h6>
  </div>
  <div class="card-body">
    <canvas id="avanceOperarios" height="250"></canvas>
  </div>
</div>

<script>
  const ctxOperarios = document.getElementById('avanceOperarios').getContext('2d');
  new Chart(ctxOperarios, {
    type: 'bar',
    data: {
      labels: <?= json_encode($labelsOperarios) ?>,
      datasets: [{
        label: '% Tareas Completadas',
        data: <?= json_encode($dataTareas) ?>,
        backgroundColor: 'rgba(54, 162, 235, 0.7)',
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: { enabled: true },
        legend: { display: false }
      },
      scales: {
        y: {
          beginAtZero: true,
          max: 100,
          title: { display: true, text: '%' }
        }
      }
    }
  });
</script>

    <?php elseif ($rol === 'cliente'): ?>
      <div class="row">
        <div class="col-12">
          <h5 class="mb-3">Mis Pedidos</h5>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Proyecto</th>
                <th>Piezas</th>
                <th>Estado</th>
                <th>Fecha Entrega</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (pedidosCliente($pdo, $clienteId) as $pedido): ?>
                <tr>
                  <td><?= htmlspecialchars($pedido['proyecto']) ?></td>
                  <td><?= htmlspecialchars($pedido['piezas']) ?></td>
                  <td><?= htmlspecialchars($pedido['estado']) ?></td>
                  <td><?= htmlspecialchars($pedido['fecha_entrega_real']) ?></td>
                </tr>
              <?php endforeach; ?>
              <?php if (empty(pedidosCliente($pdo, $clienteId))): ?>
                <tr><td colspan="4" class="text-center text-muted">No tienes pedidos registrados.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    <?php else: ?>
      <div class="alert alert-warning">
        Rol no reconocido o no tienes permisos para ver esta sección.
      </div>
    <?php endif; ?>
  </div>
</div>
