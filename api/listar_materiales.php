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
    $condicion = "WHERE nombre LIKE :busqueda OR descripcion LIKE :busqueda OR unidad_medida LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Contar total de registros
$totalQuery = $pdo->prepare("SELECT COUNT(*) FROM materiales $condicion");
$totalQuery->execute($params);
$totalRegistros = $totalQuery->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Obtener registros con paginación
$sql = "SELECT * FROM materiales $condicion ORDER BY nombre ASC LIMIT $offset, $porPagina";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Renderizar tabla
$html = '';
foreach ($materiales as $material) {
    // Botón eliminar solo para administradores
    $linkEliminar = ($_SESSION['usuario']['rol'] === 'Administrador') 
        ? "<a href='#' class='btn btn-sm btn-danger btn-eliminar' data-id='{$material['id']}' title='Eliminar'>
                <i class='bi bi-trash-fill'></i>
           </a>"
        : '';

    $html .= "
        <tr>
            <td>{$material['id']}</td>
            <td>" . htmlspecialchars($material['nombre']) . "</td>
            <td>" . htmlspecialchars($material['descripcion']) . "</td>
            <td>" . htmlspecialchars($material['unidad_medida']) . "</td>
            <td>" . number_format($material['stock_actual'], 0) . "</td>
            <td>" . number_format($material['stock_minimo'], 0) . "</td>
            <td class='text-center'>
                <a href='index.php?vista=editar_materiales&id={$material['id']}' class='btn btn-sm btn-outline-warning' title='Editar'>
                    <i class='bi bi-pencil-square'></i>
                </a>
                $linkEliminar
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
    'html' => $html ?: "<tr><td colspan='7' class='text-muted text-center py-3'>No se encontraron materiales.</td></tr>",
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);
