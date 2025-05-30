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
    $condicion = "WHERE nombre LIKE :busqueda OR email LIKE :busqueda OR telefono LIKE :busqueda OR codigo_acceso LIKE :busqueda OR codigo LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Contar total de registros
$totalQuery = $pdo->prepare("SELECT COUNT(*) FROM clientes $condicion");
$totalQuery->execute($params);
$totalRegistros = $totalQuery->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Obtener registros con paginación
$sql = "SELECT * FROM clientes $condicion ORDER BY nombre DESC LIMIT $offset, $porPagina";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Renderizar tabla
$html = '';
foreach ($clientes as $cliente) {
    $linkAdmin = ($_SESSION['usuario']['rol'] === 'Administrador') 
        ? " <td class='text-center'>
                <a href='index.php?vista=editar_clientes&id={$cliente['id']}' class='btn btn-sm btn-outline-warning'>
                    <i class='bi bi-pencil-square'></i>
                </a>
                <a href='#' class='btn btn-sm btn-danger btn-eliminar' data-id='{$cliente['id']}'><i class='bi bi-trash-fill'></i></a>
            </td> "
        : '';
    $linkDesign = ($_SESSION['usuario']['rol'] === 'Diseñador') 
        ? " "
        : '';

    $html .= "
        <tr>
            <td>{$cliente['id']}</td>
            <td>" . htmlspecialchars($cliente['nombre']) . "</td>
            <td>" . htmlspecialchars($cliente['email']) . "</td>
            <td>" . htmlspecialchars($cliente['codigo_acceso']) . "</td>
            <td>" . htmlspecialchars($cliente['codigo']) . "</td>
            <td>" . htmlspecialchars($cliente['telefono']) . "</td>
            <td>" . htmlspecialchars($cliente['direccion']) . "</td>
            <td>" . date("d/m/Y H:i", strtotime($cliente['creado_en'])) . "</td>
            $linkAdmin
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
    'html' => $html ?: "<tr><td colspan='9' class='text-muted text-center py-3'>No se encontraron clientes.</td></tr>",
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);
