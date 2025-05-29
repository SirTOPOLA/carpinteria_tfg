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
    $condicion = "WHERE c.nombre LIKE :busqueda OR c.email LIKE :busqueda OR p.nombre LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Contar total de registros
$totalQuery = $pdo->prepare("
    SELECT COUNT(*) FROM solicitudes_proyecto sp
    INNER JOIN clientes c ON sp.cliente_id = c.id
    INNER JOIN proyectos p ON sp.proyecto_id = p.id
    $condicion
");
$totalQuery->execute($params);
$totalRegistros = $totalQuery->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Obtener registros con paginación
$sql = "
    SELECT sp.*, c.nombre AS cliente_nombre, p.nombre AS proyecto_nombre 
    FROM solicitudes_proyecto sp
    INNER JOIN clientes c ON sp.cliente_id = c.id
    INNER JOIN proyectos p ON sp.proyecto_id = p.id
    $condicion
    ORDER BY sp.fecha_solicitud DESC
    LIMIT $offset, $porPagina
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Renderizar tabla
$html = '';
foreach ($solicitudes as $solicitud) {
    $btnDetalle = "
        <a href='views/private/cotizacion.php?id={$solicitud['id']}' target='_blank' class='btn btn-sm btn-outline-primary'>
            <i class='bi bi-file-earmark-text'></i> Detalles
        </a>
    ";

    $html .= "
        <tr>
            <td>{$solicitud['id']}</td>
            <td>" . htmlspecialchars($solicitud['cliente_nombre']) . "</td>
            <td>" . htmlspecialchars($solicitud['proyecto_nombre']) . "</td>
            <td>" . htmlspecialchars($solicitud['descripcion']) . "</td>
            <td>" . date("d/m/Y", strtotime($solicitud['fecha_solicitud'])) . "</td>
            <td><span class='badge bg-secondary'>{$solicitud['estado']}</span></td>
            <td>S/ " . number_format($solicitud['estimacion_total'], 2) . "</td>
            <td class='text-center'>
                $btnDetalle
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
