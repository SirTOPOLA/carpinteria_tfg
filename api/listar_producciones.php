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

$condicion = '';
$params = [];

if ($termino !== '') {
    $condicion = "WHERE pr.nombre LIKE :busqueda OR e.nombre LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Total de registros
$sqlTotal = "SELECT COUNT(*) 
            FROM producciones p
            INNER JOIN proyectos pr ON p.proyecto_id = pr.id
            INNER JOIN empleados e ON p.responsable_id = e.id
            $condicion";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->execute($params);
$totalRegistros = $stmtTotal->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Consulta de producciones
$sql = "SELECT 
            prod.*,
                pr.estado AS estado_proyecto, 
                pr.nombre AS proyecto_nombre, 
                emp.nombre AS empleado_nombre
                FROM producciones prod
                LEFT JOIN proyectos pr ON prod.proyecto_id = pr.id                 
                LEFT JOIN empleados emp ON prod.responsable_id = emp.id
        $condicion
        ORDER BY pr.nombre ASC
        LIMIT $offset, $porPagina";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$producciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// HTML
$html = '';
foreach ($producciones as $prod) {
    $linkAdmin = ($_SESSION['usuario']['rol'] === 'Administrador') 
    ? " <td class='text-center'>
            <a href='index.php?vista=editar_producciones&id={$prod['id']}' class='btn btn-sm btn-outline-warning' title='Editar'>
                <i class='bi bi-pencil-square'></i>
            </a>
             <a href='#' class='btn btn-sm btn-danger btn-eliminar' data-id='{$prod['id']}' title='Eliminar'>
            <i class='bi bi-trash-fill'></i>
       </a>
            <!--
            <a href='registrar_proceso_produccionesid={$prod['id']}' class='btn btn-sm btn-outline-primary' title='Procesar'>
                <i class='bi bi-play-circle'></i>
            </a>
            -->
        </td>"
    : '';
    $html .= "
    <tr>
        <td>{$prod['id']}</td>
        <td>" . htmlspecialchars($prod['proyecto_nombre']) . "</td>
        <td>" . htmlspecialchars($prod['fecha_inicio']) . "</td>
        <td>" . htmlspecialchars($prod['fecha_fin']) . "</td>
        <td>" . htmlspecialchars($prod['estado_proyecto']) . "</td>
        <td>" . htmlspecialchars($prod['estado']) . "</td>
        <td>" . htmlspecialchars($prod['empleado_nombre']) . "</td>
        <td>" . htmlspecialchars($prod['created_at']) . "</td>
        $linkAdmin 
    </tr>
    ";
}

$html = $html ?: "<tr><td colspan='4' class='text-center text-muted'>No se encontraron producciones.</td></tr>";

// Paginación
$paginacion = '';
if ($pagina > 1) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-produccion' data-pagina='" . ($pagina - 1) . "'>&laquo;</button>";
}
for ($i = max(1, $pagina - 2); $i <= min($totalPaginas, $pagina + 2); $i++) {
    $active = ($i === $pagina) ? 'btn-primary' : 'btn-outline-secondary';
    $paginacion .= "<button class='btn btn-sm $active pagina-produccion' data-pagina='$i'>$i</button>";
}
if ($pagina < $totalPaginas) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-produccion' data-pagina='" . ($pagina + 1) . "'>&raquo;</button>";
}

// Resumen
$desde = ($pagina - 1) * $porPagina + 1;
$hasta = min($pagina * $porPagina, $totalRegistros);
$resumen = "Mostrando $desde-$hasta de $totalRegistros resultados";

// Respuesta JSON
echo json_encode([
    'success' => true,
    'html' => $html,
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);
