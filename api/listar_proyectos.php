<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../config/conexion.php';
header('Content-Type: application/json');

// Parámetros
$pagina = isset($_POST['pagina']) ? max(1, intval($_POST['pagina'])) : 1;
$termino = trim($_POST['termino'] ?? '');
$porPagina = 5;
$offset = ($pagina - 1) * $porPagina;

// Condiciones de búsqueda
$condicion = '';
$params = [];

if ($termino !== '') {
    $condicion = "WHERE nombre LIKE :busqueda OR estado LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Total de registros
$sqlTotal = "SELECT COUNT(*) FROM proyectos $condicion";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->execute($params);
$totalRegistros = $stmtTotal->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Consulta de datos
$sql = "SELECT * FROM proyectos 
        $condicion 
        ORDER BY creado_en DESC 
        LIMIT $offset, $porPagina";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// HTML
$html = '';
foreach ($proyectos as $p) {
    $linkEliminar = ($_SESSION['usuario']['rol'] === 'Administrador') 
    ? "<a href='#' class='btn btn-sm btn-danger btn-eliminar' data-id='{$p['id']}' title='Eliminar'>
            <i class='bi bi-trash-fill'></i>
       </a>"
    : '';
    $html .= "
    <tr>
        <td>{$p['id']}</td>
        <td>" . htmlspecialchars($p['nombre']) . "</td>
        <td>" . htmlspecialchars($p['descripcion']) . "</td>
        <td>" . htmlspecialchars($p['estado']) . "</td>
        <td>" . htmlspecialchars($p['fecha_inicio']) . "</td>
        <td>" . htmlspecialchars($p['fecha_entrega']) . "</td>
        <td>" . htmlspecialchars($p['creado_en']) . "</td>
        <td class='text-center'>
            <a href='index.php?vista=editar_proyectos&id={$p['id']}' class='btn btn-sm btn-outline-warning' title='Editar'>
                <i class='bi bi-pencil-square'></i>
            </a>
             $linkEliminar
        </td>
    </tr>";
}
$html = $html ?: "<tr><td colspan='8' class='text-center text-muted'>No se encontraron proyectos.</td></tr>";

// Paginación
$paginacion = '';
if ($pagina > 1) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-proyecto' data-pagina='" . ($pagina - 1) . "'>&laquo;</button>";
}
for ($i = max(1, $pagina - 2); $i <= min($totalPaginas, $pagina + 2); $i++) {
    $active = ($i === $pagina) ? 'btn-primary' : 'btn-outline-secondary';
    $paginacion .= "<button class='btn btn-sm $active pagina-proyecto' data-pagina='$i'>$i</button>";
}
if ($pagina < $totalPaginas) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-proyecto' data-pagina='" . ($pagina + 1) . "'>&raquo;</button>";
}

// Resumen
$desde = ($pagina - 1) * $porPagina + 1;
$hasta = min($pagina * $porPagina, $totalRegistros);
$resumen = "Mostrando $desde-$hasta de $totalRegistros resultados";

// Respuesta
echo json_encode([
    'success' => true,
    'html' => $html,
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);
