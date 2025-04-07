<?php
require_once '../includes/conexion.php';

// Por defecto: últimos 30 días
$fecha_fin = date('Y-m-d');
$fecha_inicio = date('Y-m-d', strtotime('-30 days'));

// Obtener filtro de tipo
$filtro_tipo = $_GET['filtro_tipo'] ?? '';
$busqueda = trim($_GET['busqueda'] ?? '');
$filtro_estatus = $_GET['estatus'] ?? '';
$filtro_activo = false;

// Aplicar lógica según tipo de filtro
$condicion = [];
$params = [];

switch ($filtro_tipo) {
    case 'id':
        if (is_numeric($busqueda)) {
            $condicion[] = 'id = :busqueda';
            $params[':busqueda'] = $busqueda;
            $filtro_activo = true;
        }
        break;
    case 'nombre':
        if (!empty($busqueda)) {
            $condicion[] = 'nombre LIKE :busqueda';
            $params[':busqueda'] = "%$busqueda%";
            $filtro_activo = true;
        }
        break;
    case 'fecha':
        if (!empty($_GET['fecha_inicio']) && !empty($_GET['fecha_fin'])) {
            $fecha_inicio = $_GET['fecha_inicio'];
            $fecha_fin = $_GET['fecha_fin'];
            $condicion[] = 'DATE(fecha_entrega) BETWEEN :inicio AND :fin';
            $params[':inicio'] = $fecha_inicio;
            $params[':fin'] = $fecha_fin;
            $filtro_activo = true;
        }
        break;
}

if (!empty($filtro_estatus)) {
    $condicion[] = 'estatus = :estatus';
    $params[':estatus'] = $filtro_estatus;
    $filtro_activo = true;
}

function obtenerProyectos(PDO $pdo, array $condiciones, array $params): array {
    try {
        $sql = "SELECT id, nombre, fecha_entrega, estatus FROM proyectos";
        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }
        $sql .= " ORDER BY fecha_entrega ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener proyectos: " . $e->getMessage());
        return [];
    }
}
$proyectos = obtenerProyectos($pdo, $condicion, $params);

// Simulación de tareas diarias
$tareas = [
    ['Tarea' => 'Corte de madera', 'Responsable' => 'Juan', 'Hora' => '09:00'],
    ['Tarea' => 'Montaje de estructura', 'Responsable' => 'Luis', 'Hora' => '11:00'],
    ['Tarea' => 'Pulido y barnizado', 'Responsable' => 'Ana', 'Hora' => '15:00']
];

// Simulación de notificaciones
$notificaciones = array_filter($proyectos, function ($p) {
    $dias = (new DateTime())->diff(new DateTime($p['fecha_entrega']))->days;
    return $dias <= 3;
});
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe General - Carpintería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<div class="container my-4">
    <h2 class="mb-4">Informe General</h2>

    <!-- Filtro -->
    <form method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-md-2">
            <label class="form-label">Filtrar por</label>
            <select name="filtro_tipo" id="filtro_tipo" class="form-select" required onchange="toggleInputs()">
                <option value="">-- Selecciona --</option>
                <option value="id" <?= $filtro_tipo == 'id' ? 'selected' : '' ?>>ID</option>
                <option value="nombre" <?= $filtro_tipo == 'nombre' ? 'selected' : '' ?>>Nombre</option>
                <option value="fecha" <?= $filtro_tipo == 'fecha' ? 'selected' : '' ?>>Fecha de entrega</option>
            </select>
        </div>
        <div class="col-md-2" id="grupo_busqueda">
            <label class="form-label">Buscar</label>
            <input type="text" name="busqueda" class="form-control" value="<?= htmlspecialchars($busqueda) ?>">
        </div>
        <div class="col-md-2 d-none" id="grupo_fecha_inicio">
            <label class="form-label">Desde</label>
            <input type="date" name="fecha_inicio" class="form-control" value="<?= $fecha_inicio ?>">
        </div>
        <div class="col-md-2 d-none" id="grupo_fecha_fin">
            <label class="form-label">Hasta</label>
            <input type="date" name="fecha_fin" class="form-control" value="<?= $fecha_fin ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Estatus</label>
            <select name="estatus" class="form-select">
                <option value="">Todos</option>
                <option value="pendiente" <?= $filtro_estatus == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                <option value="en curso" <?= $filtro_estatus == 'en curso' ? 'selected' : '' ?>>En curso</option>
                <option value="completado" <?= $filtro_estatus == 'completado' ? 'selected' : '' ?>>Completado</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-dark w-100">Aplicar</button>
        </div>
    </form>

    <!-- Cards -->
    <div class="row">
        <?php
        $cards = [
            ['Materiales', 'materiales.php', 'primary'],
            ['Productos', 'productos.php', 'success'],
            ['Ventas', 'ventas.php', 'info'],
            ['Clientes', 'clientes.php', 'dark'],
        ];
        foreach ($cards as [$titulo, $link, $color]) {
            echo <<<HTML
            <div class="col-md-3 mb-3">
                <div class="card border-$color shadow">
                    <div class="card-body">
                        <h5 class="text-$color">$titulo</h5>
                        <p class="fs-4 fw-bold">Ver detalles</p>
                        <a href="$link" class="btn btn-outline-$color btn-sm">Ir</a>
                    </div>
                </div>
            </div>
            HTML;
        }
        ?>
    </div>

    <!-- Gráficos -->
    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <canvas id="grafico1"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="grafico2"></canvas>
        </div>
    </div>

    <!-- Tabla de proyectos -->
    <div class="card shadow mb-4">
        <div class="card-header bg-secondary text-white"><strong>Proyectos próximos</strong></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-0">
                    <thead class="table-light">
                        <tr><th>ID</th><th>Nombre</th><th>Entrega</th><th>Días restantes</th><th>Estatus</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proyectos as $p): 
                            $dias = (new DateTime($p['fecha_entrega']))->diff(new DateTime())->format('%r%a');
                            $restantes = (int)$dias < 0 ? "Vencido" : "$dias días";
                        ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><?= htmlspecialchars($p['nombre']) ?></td>
                            <td><?= $p['fecha_entrega'] ?></td>
                            <td><?= $restantes ?></td>
                            <td><?= ucfirst($p['estatus']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($proyectos)): ?>
                        <tr><td colspan="5" class="text-center text-muted">No hay proyectos encontrados</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Notificaciones -->
    <div class="alert alert-warning">
        <strong>Notificaciones:</strong>
        <?php if (!empty($notificaciones)): ?>
            <ul class="mb-0">
                <?php foreach ($notificaciones as $n): ?>
                <li>Proyecto <strong><?= $n['nombre'] ?></strong> vence pronto (<?= $n['fecha_entrega'] ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            No hay entregas próximas.
        <?php endif; ?>
    </div>

    <!-- Tareas diarias -->
    <div class="card">
        <div class="card-header bg-info text-white"><strong>Tareas del día</strong></div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead><tr><th>Tarea</th><th>Responsable</th><th>Hora</th></tr></thead>
                <tbody>
                    <?php foreach ($tareas as $t): ?>
                    <tr><td><?= $t['Tarea'] ?></td><td><?= $t['Responsable'] ?></td><td><?= $t['Hora'] ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function toggleInputs() {
    const tipo = document.getElementById('filtro_tipo').value;
    document.getElementById('grupo_busqueda').classList.toggle('d-none', tipo === 'fecha');
    document.getElementById('grupo_fecha_inicio').classList.toggle('d-none', tipo !== 'fecha');
    document.getElementById('grupo_fecha_fin').classList.toggle('d-none', tipo !== 'fecha');
}
toggleInputs();
</script>
<script>
new Chart(document.getElementById('grafico1'), {
    type: 'bar',
    data: {
        labels: ['Materiales', 'Productos', 'Clientes', 'Ventas'],
        datasets: [{
            label: 'Totales',
            data: [10, 25, 15, 30],
            backgroundColor: ['#0d6efd', '#198754', '#0dcaf0', '#212529']
        }]
    }
});
new Chart(document.getElementById('grafico2'), {
    type: 'doughnut',
    data: {
        labels: ['Completados', 'En curso', 'Pendientes'],
        datasets: [{
            data: [12, 7, 3],
            backgroundColor: ['#198754', '#ffc107', '#dc3545']
        }]
    }
});
</script>
</body>
</html>
