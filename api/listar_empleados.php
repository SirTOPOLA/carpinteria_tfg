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
    $condicion = "WHERE nombre LIKE :busqueda OR apellido LIKE :busqueda OR email LIKE :busqueda OR codigo LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Total de registros
$sqlTotal = "SELECT COUNT(*) FROM empleados $condicion";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->execute($params);
$totalRegistros = $stmtTotal->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Registros paginados
$sql = "SELECT * FROM empleados $condicion ORDER BY nombre ASC LIMIT $offset, $porPagina";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// HTML de tabla
$html = '';
foreach ($empleados as $e) {
    $nombreCompleto = htmlspecialchars($e['nombre'] . ' ' . $e['apellido']);
    $salario = htmlspecialchars($e['salario'] ?? 'Sin definir');
    $fecha = date('d/m/Y', strtotime($e['fecha_ingreso']));
    
    $botonEliminar = ($_SESSION['usuario']['rol'] === 'Administrador') 
        ? "<a href='#' class='btn btn-sm btn-danger btn-eliminar' data-id='{$e['id']}'><i class='bi bi-trash'></i></a>"
        : '';

    $html .= "
        <tr>
            <td>{$e['id']}</td>
            <td>{$nombreCompleto}</td>
            <td>" . htmlspecialchars($e['codigo']) . "</td>
            <td>" . htmlspecialchars($e['email']) . "</td>
            <td>" . htmlspecialchars($e['telefono']) . "</td>
            <td>" . htmlspecialchars($e['direccion']) . "</td>
            <td>" . htmlspecialchars($e['horario_trabajo']) . "</td>
            <td>{$salario}</td>
            <td>{$fecha}</td>
            <td>
                <a href='index.php?vista=editar_empleado&id=" . urlencode($e['id']) . "' class='btn btn-sm btn-outline-warning'>
                    <i class='bi bi-pencil-square'></i>
                </a>
                $botonEliminar
            </td>
        </tr>
    ";
}

$html = $html ?: "<tr><td colspan='10' class='text-center text-muted'>No se encontraron empleados.</td></tr>";

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

// Resumen
$desde = ($pagina - 1) * $porPagina + 1;
$hasta = min($pagina * $porPagina, $totalRegistros);
$resumen = "Mostrando $desde-$hasta de $totalRegistros resultados";

// JSON de respuesta
echo json_encode([
    'success' => true,
    'html' => $html,
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);
