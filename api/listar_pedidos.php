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

// Contar total registros
$totalQuery = $pdo->prepare("
    SELECT COUNT(*) FROM pedidos p
    INNER JOIN clientes c ON p.cliente_id = c.id
    $condicion
");
$totalQuery->execute($params);
$totalRegistros = $totalQuery->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Obtener registros con paginación, esta vez con estado nombre
$sql = "
    SELECT p.*, c.nombre AS cliente_nombre, e.nombre AS estado_nombre, e.id AS estado_id
    FROM pedidos p
    INNER JOIN clientes c ON p.cliente_id = c.id
    INNER JOIN estados e ON p.estado_id = e.id
    $condicion
    ORDER BY p.fecha_solicitud DESC
    LIMIT $offset, $porPagina
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ------- modernizar el stado -- */
function getEstadoBadgeClass($estado) {
    $estado = strtolower($estado);
    return match($estado) {
        'cotizado' => 'bg-warning text-dark',   // Amarillo suave
        'aprobado' => 'bg-success',             // Verde
        'entregado' => 'bg-primary',            // Azul
        'cancelado' => 'bg-danger',             // Rojo
        default => 'bg-secondary',              // Gris por defecto
    };
}

$html = '';
foreach ($solicitudes as $solicitud) {

    $btnDetalle = (($_SESSION['usuario']['rol'] === 'Administrador') || ($_SESSION['usuario']['rol'] === 'Diseñador'))
        ? "<a href='views/private/cotizacion.php?id={$solicitud['id']}' target='_blank' class='btn btn-sm btn-outline-primary'>
                <i class='bi bi-file-earmark-text'></i> Detalles
           </a>"
        : '';

    // Botón cambiar estado, activo para estados que no sean "entregado" o "cancelado"
    $estadosSinCambio = ['entregado', 'cancelado'];

    if (!in_array(strtolower($solicitud['estado_nombre']), $estadosSinCambio)) {
        $btnEstado = "
            <button class='btn btn-sm btn-outline-success cambiar-estado-btn' 
                data-id='{$solicitud['id']}' 
                data-estado-id='{$solicitud['estado_id']}'
                data-estado-nombre='{$solicitud['estado_nombre']}'
                data-bs-toggle='modal' 
                data-bs-target='#modalCambiarEstado'>
                <i class='bi bi-arrow-repeat'></i> Cambiar Estado
            </button>
        ";
    } else {
        $btnEstado = '';
    }

    $html .= "
        <tr>
            <td>{$solicitud['id']}</td>
            <td>" . htmlspecialchars($solicitud['cliente_nombre']) . "</td>
            <td>" . htmlspecialchars($solicitud['proyecto']) . "</td>
            <td>" . htmlspecialchars($solicitud['descripcion']) . "</td>
            <td>" . date("d/m/Y", strtotime($solicitud['fecha_solicitud'])) . "</td>
           <td>
                <span class='badge " . getEstadoBadgeClass($solicitud['estado_nombre']) . "'>
                    " . ucfirst(htmlspecialchars($solicitud['estado_nombre'])) . "
                </span>
            </td>

            <td>XAF/ " . number_format($solicitud['estimacion_total'], 2) . "</td>
            <td class='text-center'>
                $btnDetalle 
                $btnEstado
            </td>
        </tr>
    ";
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
$resumen = "Mostrando $desde-$hasta de $totalRegistros resultados";

echo json_encode([
    'success' => true,
    'html' => $html ?: "<tr><td colspan='8' class='text-muted text-center py-3'>No se encontraron solicitudes.</td></tr>",
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);
