<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require '../config/conexion.php';
header('Content-Type: application/json');

$pagina = isset($_POST['pagina']) ? max(1, intval($_POST['pagina'])) : 1;
$termino = trim($_POST['termino'] ?? '');
$porPagina = 5;
$offset = ($pagina - 1) * $porPagina;

$condicion = '';
$params = [];

if ($termino !== '') {
    $condicion = "WHERE c.nombre LIKE :busqueda OR c.email LIKE :busqueda OR p.proyecto LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Obtener total de clientes únicos con pedidos
$totalClientesQuery = $pdo->prepare("
    SELECT COUNT(DISTINCT c.id)
    FROM pedidos p
    INNER JOIN clientes c ON p.cliente_id = c.id
    $condicion
");
$totalClientesQuery->execute($params);
$totalRegistros = $totalClientesQuery->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Obtener los clientes en la página actual
$clientesQuery = $pdo->prepare("
    SELECT DISTINCT c.id, c.nombre
    FROM pedidos p
    INNER JOIN clientes c ON p.cliente_id = c.id
    $condicion
    ORDER BY c.nombre ASC
    LIMIT $offset, $porPagina
");
$clientesQuery->execute($params);
$clientes = $clientesQuery->fetchAll(PDO::FETCH_ASSOC);

// Recolectar los IDs de clientes en esta página
$idsClientes = array_column($clientes, 'id');
if (empty($idsClientes)) {
    echo json_encode([
        'success' => true,
        'html' => "<tr><td colspan='8' class='text-muted text-center py-3'>No se encontraron solicitudes.</td></tr>",
        'paginacion' => '',
        'resumen' => '0 resultados',
    ]);
    exit;
}

// Obtener todos los pedidos de esos clientes
$inClause = implode(',', array_fill(0, count($idsClientes), '?'));
$sql = "
    SELECT 
        p.*, 
        c.nombre AS cliente_nombre, 
        e.nombre AS estado_nombre, 
        e.id AS estado_id,
        s.nombre AS servicio_nombre,
        s.precio_base AS servicio_precio
    FROM pedidos p
    INNER JOIN clientes c ON p.cliente_id = c.id
    INNER JOIN estados e ON p.estado_id = e.id
    LEFT JOIN servicios s ON p.servicio_id = s.id
    WHERE c.id IN ($inClause)
";
$stmt = $pdo->prepare($sql);
$stmt->execute($idsClientes);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar por cliente
$agrupados = [];
foreach ($pedidos as $pedido) {
    $agrupados[$pedido['cliente_nombre']][] = $pedido;
}

// Clase visual para estado
function getEstadoBadgeClass($estado)
{
    return match (strtolower($estado)) {
        'cotizado' => 'bg-secondary',
        'aprobado' => 'bg-success',
        'entregado' => 'bg-primary',
        'en_produccion' => 'bg-warning text-dark',
        'cancelado' => 'bg-danger',
        default => 'bg-secondary',
    };
}

// Construir HTML 
$html = '';
foreach ($agrupados as $cliente => $listaPedidos) {
    $totalPiezas = 0;
    $totalAdelanto = 0;
    $totalEstimado = 0;

    foreach ($listaPedidos as $pedido) {
        $totalPiezas += $pedido['piezas'];
        $totalAdelanto += $pedido['adelanto'];
        $totalEstimado += $pedido['estimacion_total'];
    }

    $html .= "
    <tr class='table-primary'>
        <td colspan='9' class='fw-bold'>
            <i class='bi bi-person-circle me-1'></i> " . htmlspecialchars($cliente) . " 
            <span class='float-end'>
                <small class='text-muted'>
                    <i class='bi bi-layers me-1'></i>Piezas: $totalPiezas |
                    <i class='bi bi-cash-stack me-1'></i>Adelanto: XAF/ " . number_format($totalAdelanto, 0) . " |
                    <i class='bi bi-currency-exchange me-1'></i>Total estimado: XAF/ " . number_format($totalEstimado, 0) . "
                </small>
            </span>
        </td>
    </tr>";

    foreach ($listaPedidos as $pedido) {
        $btnPDF = (($_SESSION['usuario']['rol'] === 'Administrador') || ($_SESSION['usuario']['rol'] === 'Diseñador'))
            ? "<a href='views/private/cotizacion.php?id={$pedido['id']}' target='_blank' class='btn btn-sm btn-outline-success me-1'><i class='bi bi-file-earmark-pdf'></i></a>"
            : '';

        $btnEstado = '';
        if (strtolower($pedido['estado_nombre']) === 'cotizado') {
            $btnEstado = "
                <button class='btn btn-sm btn-outline-primary cambiar-estado-btn' 
    data-id='{$pedido['id']}' 
    data-total='{$pedido['estimacion_total']}'
    data-estado-id='{$pedido['estado_id']}'
    data-estado-nombre='{$pedido['estado_nombre']}'
    data-bs-toggle='modal' 
    data-bs-target='#modalCambiarEstado'>
    <i class='bi bi-arrow-repeat'></i>
</button>

            ";


        }

        // Mostrar nombre y precio del servicio si existe
        $servicio = '';
        if (!empty($pedido['servicio_nombre'])) {
            $servicio = "<div class='small text-muted'>
                            <i class='bi bi-hammer'></i> " . htmlspecialchars($pedido['servicio_nombre']) . " 
                            (XAF/ " . number_format($pedido['servicio_precio'], 0) . ")
                         </div>";
        }

        $html .= "
        <tr>
            <td>{$pedido['id']}</td>
            <td>" . htmlspecialchars($pedido['proyecto']) . "</td>
            <td>" . htmlspecialchars($pedido['descripcion']) . "$servicio</td>
            <td>{$pedido['piezas']}</td>
            <td>XAF/ " . number_format($pedido['adelanto'], 0) . "</td>
            <td>XAF/ " . number_format($pedido['estimacion_total'], 0) . "</td>
            <td>  " . number_format($pedido['fecha_entrega'], 0) . " días</td>
            <td>" . date("d/m/Y", strtotime($pedido['fecha_solicitud'])) . "</td>
            <td><span class='badge " . getEstadoBadgeClass($pedido['estado_nombre']) . "'>" . ucfirst(htmlspecialchars($pedido['estado_nombre'])) . "</span></td>
            <td class='text-center'>$btnPDF $btnEstado</td>

            
        </tr>";
    }
}

// Paginación
$paginacion = '';
if ($pagina > 1) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link' data-pagina='" . ($pagina - 1) . "'>&laquo; Anterior</button>";
}

$rango = 2;
$inicio = max(1, $pagina - $rango);
$fin = min($totalPaginas, $pagina + $rango);

for ($i = $inicio; $i <= $fin; $i++) {
    $active = $i === $pagina ? 'btn-primary' : 'btn-outline-secondary';
    $paginacion .= "<button class='btn btn-sm $active pagina-link' data-pagina='$i'>$i</button>";
}

if ($pagina < $totalPaginas) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link' data-pagina='" . ($pagina + 1) . "'>Siguiente &raquo;</button>";
}

$desde = ($pagina - 1) * $porPagina + 1;
$hasta = min($pagina * $porPagina, $totalRegistros);
$resumen = "Mostrando $desde-$hasta de $totalRegistros clientes con pedidos";

echo json_encode([
    'success' => true,
    'html' => $html,
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);
