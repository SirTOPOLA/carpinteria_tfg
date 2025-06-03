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
    SELECT COUNT(*) FROM pedidos p
    INNER JOIN clientes c ON p.cliente_id = c.id
    $condicion
");
$totalQuery->execute($params);
$totalRegistros = $totalQuery->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Obtener registros con paginación
$sql = "
    SELECT p.*, c.nombre AS cliente_nombre
    FROM pedidos p
    INNER JOIN clientes c ON p.cliente_id = c.id
    $condicion
    ORDER BY p.fecha_solicitud DESC
    LIMIT $offset, $porPagina
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Renderizar tabla
$html = '';
foreach ($solicitudes as $solicitud) {
    $btnDetalle =(($_SESSION['usuario']['rol'] === 'Administrador') || ($_SESSION['usuario']['rol'] === 'Diseñador')) 
    ? "
        <a href='views/private/cotizacion.php?id={$solicitud['id']}' target='_blank' class='btn btn-sm btn-outline-primary'>
            <i class='bi bi-file-earmark-text'></i> Detalles
        </a>
    ": '';
  
    
     
    $btnEstado = '';
    if (strtolower($solicitud['estado']) === 'cotizado') {
        $btnEstado = "
        <button class='btn btn-sm btn-outline-success cambiar-estado-btn' 
                data-id='{$solicitud['id']}' 
                data-estado='{$solicitud['estado']}' 
                 data-tipo='pedido'
                data-bs-toggle='modal' 
                data-bs-target='#modalCambiarEstado'>
            <i class='bi bi-arrow-repeat'></i> Cambiar Estado
        </button>
        ";
    }
    

$html .= "
    <tr>
        <td>{$solicitud['id']}</td>
        <td>" . htmlspecialchars($solicitud['cliente_nombre']) . "</td>
        <td>" . htmlspecialchars($solicitud['proyecto']) . "</td>
        <td>" . htmlspecialchars($solicitud['descripcion']) . "</td>
        <td>" . date("d/m/Y", strtotime($solicitud['fecha_solicitud'])) . "</td>
       <td><span class='badge bg-secondary'>" . htmlspecialchars($solicitud['estado']) . "</span></td>

        <td>S/ " . number_format($solicitud['estimacion_total'], 2) . "</td>
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
