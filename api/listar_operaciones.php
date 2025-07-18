<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../config/conexion.php';
header('Content-Type: application/json');

$pagina = isset($_POST['pagina']) ? max(1, intval($_POST['pagina'])) : 1;
$termino = trim($_POST['termino'] ?? '');
$porPagina = 5;
$offset = ($pagina - 1) * $porPagina;

$params = [];
$condicion = '';
$filtroResponsable = '';
$esAdministrador = $_SESSION['usuario']['rol'] === 'Administrador';
$empleadoId = $_SESSION['usuario']['empleado_id'] ?? null;

// Filtro por búsqueda
if ($termino !== '') {
    $condicion .= " AND (pr.proyecto LIKE :busqueda OR emp.nombre LIKE :busqueda OR emp.apellido LIKE :busqueda)";
    $params[':busqueda'] = "%$termino%";
}

// Filtro por empleado si no es administrador
if (!$esAdministrador && $empleadoId) {
    $condicion .= " AND tp.responsable_id = :empleado_id";
    $params[':empleado_id'] = $empleadoId;
}

// Obtener total de producciones
$totalQuery = $pdo->prepare("
    SELECT COUNT(DISTINCT tp.produccion_id)
    FROM tareas_produccion tp
    INNER JOIN producciones prod ON tp.produccion_id = prod.id
    INNER JOIN pedidos pr ON prod.solicitud_id = pr.id
    LEFT JOIN empleados emp ON tp.responsable_id = emp.id
    WHERE 1=1 $condicion
");
$totalQuery->execute($params);
$totalRegistros = $totalQuery->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Obtener IDs de producciones para la página actual
$produccionesQuery = $pdo->prepare("
    SELECT DISTINCT tp.produccion_id
    FROM tareas_produccion tp
    INNER JOIN producciones prod ON tp.produccion_id = prod.id
    INNER JOIN pedidos pr ON prod.solicitud_id = pr.id
    LEFT JOIN empleados emp ON tp.responsable_id = emp.id
    WHERE 1=1 $condicion
    ORDER BY tp.produccion_id DESC
    LIMIT $offset, $porPagina
");
$produccionesQuery->execute($params);
$produccionIds = array_column($produccionesQuery->fetchAll(PDO::FETCH_ASSOC), 'produccion_id');

if (empty($produccionIds)) {
    echo json_encode([
        'success' => true,
        'html' => "<tr><td colspan='8' class='text-muted text-center py-3'>No se encontraron tareas de producción.</td></tr>",
        'paginacion' => '',
        'resumen' => '0 resultados'
    ]);
    exit;
}

// Obtener tareas completas de esas producciones
$inClause = implode(',', array_fill(0, count($produccionIds), '?'));

$sql = "
    SELECT 
        tp.*, 
        pr.proyecto, pr.descripcion AS descripcion_pedido,
        emp.nombre AS empleado_nombre, emp.apellido AS empleado_apellido,
        e.nombre AS estado_nombre, prod.fecha_inicio AS produccion_inicio
    FROM tareas_produccion tp
    INNER JOIN producciones prod ON tp.produccion_id = prod.id
    INNER JOIN pedidos pr ON prod.solicitud_id = pr.id
    LEFT JOIN empleados emp ON tp.responsable_id = emp.id
    INNER JOIN estados e ON tp.estado_id = e.id
    WHERE tp.produccion_id IN ($inClause)
";

// Filtro adicional si no es administrador
if (!$esAdministrador && $empleadoId) {
    $sql .= " AND tp.responsable_id = ?";
    $produccionIds[] = $empleadoId;
}

$sql .= " ORDER BY tp.produccion_id DESC, tp.fecha_inicio ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($produccionIds);
$tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar por producción
$agrupadas = [];
foreach ($tareas as $t) {
    $agrupadas[$t['produccion_id']]['proyecto'] = $t['proyecto'];
    $agrupadas[$t['produccion_id']]['descripcion'] = $t['descripcion_pedido'];
    $agrupadas[$t['produccion_id']]['inicio'] = $t['produccion_inicio'];
    $agrupadas[$t['produccion_id']]['tareas'][] = $t;
}

// Función para clase CSS del estado
function getEstadoClass($estado)
{
    return match (strtolower($estado)) {
        'pendiente' => 'bg-warning text-dark',
        'en_progreso' => 'bg-primary text-white',
        'completado' => 'bg-success',
        'cancelada' => 'bg-danger',
        default => 'bg-secondary'
    };
}

// Construcción del HTML
$html = '';
foreach ($agrupadas as $produccionId => $datos) {
    $html .= "
    <tr class='table-info'>
        <td colspan='8'>
            <div class='fw-bold'><i class='bi bi-gear-wide-connected me-1'></i> Proyecto: " . htmlspecialchars($datos['proyecto']) . "</div>
            <div class='small text-muted'><i class='bi bi-calendar-check'></i> Inicio: " . date('d/m/Y', strtotime($datos['inicio'])) . "</div>
            <div class='small'>" . htmlspecialchars($datos['descripcion']) . "</div>
        </td>
    </tr>";

    foreach ($datos['tareas'] as $t) {
        $responsable = $t['empleado_nombre'] ? $t['empleado_nombre'] . ' ' . $t['empleado_apellido'] : '<span class="text-muted">No asignado</span>';

        $btnEditar = ($_SESSION['usuario']['rol'] === 'Administrador' || $_SESSION['usuario']['rol'] === 'Operario')
            ? "<button class='btn btn-sm btn-outline-primary me-1 editar-tarea-btn' data-id='{$t['id']}'><i class='bi bi-pencil'></i></button>"
            : '';

        $html .= "
        <tr>
            <td>#{$t['id']}</td>
            <td>" . htmlspecialchars($t['descripcion']) . "</td>
            <td>$responsable</td>
            <td>" . ($t['fecha_inicio'] ? date('d/m/Y', strtotime($t['fecha_inicio'])) : '-') . "</td>
            <td>" . ($t['fecha_fin'] ? date('d/m/Y', strtotime($t['fecha_fin'])) : '-') . "</td>
            <td><span class='badge " . getEstadoClass($t['estado_nombre']) . "'>" . ucfirst($t['estado_nombre']) . "</span></td>
            <td class='text-center'>$btnEditar</td>
        </tr>";
    }
}

// Paginación
$paginacion = '';
if ($pagina > 1) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link' data-pagina='" . ($pagina - 1) . "'>&laquo; Anterior</button>";
}
for ($i = max(1, $pagina - 2); $i <= min($totalPaginas, $pagina + 2); $i++) {
    $active = ($i === $pagina) ? 'btn-primary' : 'btn-outline-secondary';
    $paginacion .= "<button class='btn btn-sm $active pagina-link' data-pagina='$i'>$i</button>";
}
if ($pagina < $totalPaginas) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link' data-pagina='" . ($pagina + 1) . "'>Siguiente &raquo;</button>";
}

$desde = ($pagina - 1) * $porPagina + 1;
$hasta = min($pagina * $porPagina, $totalRegistros);
$resumen = "Mostrando $desde-$hasta de $totalRegistros producciones";

echo json_encode([
    'success' => true,
    'html' => $html,
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);
