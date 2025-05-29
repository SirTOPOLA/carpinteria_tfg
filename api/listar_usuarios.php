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
    $condicion = "WHERE u.usuario LIKE :busqueda OR u.rol LIKE :busqueda OR e.nombre LIKE :busqueda OR e.apellido LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Total de registros
$sqlTotal = "SELECT COUNT(*) FROM usuarios u LEFT JOIN empleados e ON u.empleado_id = e.id $condicion";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->execute($params);
$totalRegistros = $stmtTotal->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Consulta de usuarios
 
            
$sql = " SELECT u.*,
            r.nombre AS rol,
            e.nombre AS empleado_nombre,
            e.apellido AS empleado_apellido
            FROM usuarios u
            LEFT JOIN roles r ON u.rol_id = r.id
            LEFT JOIN empleados e ON u.empleado_id = e.id 
    $condicion 
    ORDER BY u.usuario ASC 
    LIMIT $offset, $porPagina
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// HTML
$html = '';
foreach ($usuarios as $usuario) {
    $link = ($_SESSION['usuario']['rol'] === 'Administrador') 
    ? "<a href='#' class='btn btn-sm btn-danger btn-eliminar' data-id='{$usuario['id']}'><i class='bi bi-trash-fill'></i></a>"
    : '';

    $imgPerfil = 'api/' . htmlspecialchars($usuario['perfil']);
    $estadoActivo = $usuario['activo'] ? '1' : '0';
    $estadoClase = $usuario['activo'] ? 'btn-success' : 'btn-danger';
    $icono = $usuario['activo'] ? 'bi-toggle-on' : 'bi-toggle-off';
    $texto = $usuario['activo'] ? 'Activado' : 'Desactivado';

    $nombreEmpleado = htmlspecialchars($usuario['empleado_nombre'] . ' ' . $usuario['empleado_apellido']);

    $html .= "
        <tr>
            <td>{$usuario['id']}</td>
            <td><img src='$imgPerfil' alt='Perfil' width='60' class='img-thumbnail'></td>
            <td>" . htmlspecialchars($usuario['usuario']) . "</td>
            <td>$nombreEmpleado</td>
            <td>" . htmlspecialchars($usuario['rol']) . "</td>
            <td class='text-center'>
                <a href='#' class='btn btn-sm $estadoClase activar-btn' data-id='{$usuario['id']}' data-estado='$estadoActivo'>
                    <i class='bi $icono'></i> $texto
                </a>
            </td>
            <td class='text-center'>
                <a href='index.php?vista=editar_usuarios&id={$usuario['id']}' class='btn btn-sm btn-outline-warning' title='Editar'>
                    <i class='bi bi-pencil-square'></i>
                </a>
                $link
            </td>
        </tr>
    ";
}

$html = $html ?: "<tr><td colspan='7' class='text-center text-muted'>No se encontraron usuarios.</td></tr>";

// Paginación
$paginacion = '';
if ($pagina > 1) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link' data-pagina='" . ($pagina - 1) . "'>&laquo;</button>";
}
for ($i = max(1, $pagina - 2); $i <= min($totalPaginas, $pagina + 2); $i++) {
    $active = ($i === $pagina) ? 'btn-primary' : 'btn-outline-secondary';
    $paginacion .= "<button class='btn btn-sm $active pagina-link' data-pagina='$i'>$i</button>";
}
if ($pagina < $totalPaginas) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link' data-pagina='" . ($pagina + 1) . "'>&raquo;</button>";
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
