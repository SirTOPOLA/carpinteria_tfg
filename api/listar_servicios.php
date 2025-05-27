<?php
if (session_status() == PHP_SESSION_NONE) {
    // Si la sesión no está iniciada, se inicia
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
    $condicion = "WHERE nombre LIKE :busqueda OR unidad LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Contar total
$totalQuery = $pdo->prepare("SELECT COUNT(*) FROM servicios $condicion");
$totalQuery->execute($params);
$totalRegistros = $totalQuery->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Obtener registros

$sql = "SELECT * FROM servicios $condicion ORDER BY id DESC LIMIT $offset, $porPagina";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Renderizar tabla
$html = '';
foreach ($servicios as $servicio) {
    $activo = $servicio['activo'] ? 'btn-success bi-toggle-on' : 'btn-danger bi-toggle-off';
    $estadoTexto = $servicio['activo'] ? 'Activado' : 'Desactivado';
 // Botón eliminar solo para administradores
 $link = ($_SESSION['usuario']['rol'] === 'Administrador') 
 ? "<a href='#' class='btn btn-sm btn-danger btn-eliminar' data-id='{$servicio['id']}'><i class='bi bi-trash-fill'></i></a>"
 : '';
    $html .= "
    <tr>
        <td>{$servicio['id']}</td>
        <td>" . htmlspecialchars($servicio['nombre']) . "</td>
        <td>" . htmlspecialchars($servicio['unidad']) . "</td>
        <td>XAF " . number_format($servicio['precio_base'], 2) . "</td>
        <td class='text-center'>
            <a href='#' class='btn btn-sm {$activo} toggle-estado' data-id='{$servicio['id']}' data-estado='{$servicio['activo']}'>
                {$estadoTexto}
            </a>
        </td>
        <td class='text-center'>
            <a href='index.php?vista=editar_servicios&id={$servicio['id']}' class='btn btn-sm btn-outline-warning'><i class='bi bi-pencil-square'></i></a>
           $link
            </td>
    </tr>";
}


$paginacion = '';

// Botón anterior
if ($pagina > 1) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link' data-pagina='" . ($pagina - 1) . "'>&laquo; Anterior</button>";
}

// Rango de páginas visibles
$rango = 2; // cantidad de páginas a mostrar antes y después de la actual
$inicio = max(1, $pagina - $rango);
$fin = min($totalPaginas, $pagina + $rango);

for ($i = $inicio; $i <= $fin; $i++) {
    $active = $i === $pagina ? 'btn-primary' : 'btn-outline-secondary';
    $paginacion .= "<button class='btn btn-sm $active pagina-link' data-pagina='$i'>$i</button>";
}

// Botón siguiente
if ($pagina < $totalPaginas) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link' data-pagina='" . ($pagina + 1) . "'>Siguiente &raquo;</button>";
}

//calculamos los valores de inicio y fin
$desde = ($pagina - 1) * $porPagina + 1;
$hasta = min($pagina * $porPagina, $totalRegistros);
$resumen = "Mostrando $desde-$hasta de $totalRegistros resultados";


echo json_encode([
    'success'=> true,
    'html' => $html ?: "<tr><td colspan='6' class='text-muted text-center py-3'>No se encontraron servicios.</td></tr>",
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);
