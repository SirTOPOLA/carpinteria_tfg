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
    $condicion = "WHERE p.proyecto LIKE :busqueda OR emp.nombre LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Total de registros
$sqlTotal = "SELECT COUNT(*) 
            FROM producciones prod
            INNER JOIN pedidos p ON prod.solicitud_id = p.id
            INNER JOIN empleados emp ON prod.responsable_id = emp.id
            $condicion";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->execute($params);
$totalRegistros = $stmtTotal->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Consulta de producciones
$sql = "SELECT 
            prod.*,
            p.proyecto AS nombre_proyecto,
            emp.nombre AS nombre_empleado,
            est.nombre AS nombre_estado
        FROM producciones prod
        LEFT JOIN pedidos p ON prod.solicitud_id = p.id                 
        LEFT JOIN empleados emp ON prod.responsable_id = emp.id
        LEFT JOIN estados est ON prod.estado_id = est.id
        $condicion
        ORDER BY p.proyecto ASC
        LIMIT $offset, $porPagina";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$producciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// HTML
$html = '';
foreach ($producciones as $prod) {
    $estadoLower = strtolower($prod['nombre_estado'] ?? '');
    $badgeColor = match ($estadoLower) {
        'pendiente'    => 'bg-secondary',
        'en proceso'   => 'bg-warning text-dark',
        'finalizado'   => 'bg-success',
        default        => 'bg-light text-dark'
    };

    $btnEstado = ($estadoLower === 'en proceso' || $estadoLower === 'pendiente') ? "
        <button class='btn btn-sm btn-outline-success cambiar-estado-btn' 
                data-id='{$prod['id']}' 
                data-estado='{$prod['nombre_estado']}'
                data-tipo='produccion'
                data-bs-toggle='modal' 
                data-bs-target='#modalCambiarEstado'>
            <i class='bi bi-arrow-repeat'></i> Cambiar Estado
        </button>" : '';

    $btnVerMateriales = "
        <button class='btn btn-sm btn-info ver-materiales-btn' 
                data-id='{$prod['id']}' 
                data-proyecto='" . htmlspecialchars($prod['nombre_proyecto']) . "' 
                data-bs-toggle='modal' 
                data-bs-target='#modalVerMateriales'>
            <i class='bi bi-box-seam'></i> Ver Materiales
        </button>";

    $acciones = '';
    if (!empty($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'Administrador') {
        $acciones = "
        <td class='text-center'>
            <a href='index.php?vista=editar_producciones&id={$prod['id']}' class='btn btn-sm btn-outline-warning' title='Editar'>
                <i class='bi bi-pencil-square'></i>
            </a>
            <a href='#' class='btn btn-sm btn-danger btn-eliminar' data-id='{$prod['id']}' title='Eliminar'>
                <i class='bi bi-trash-fill'></i>
            </a>
            $btnEstado
            $btnVerMateriales
        </td>";
    }

    $html .= " 
        <tr>
            <td>{$prod['id']}</td>
            <td>" . htmlspecialchars($prod['nombre_proyecto']) . "</td>
            <td>" . htmlspecialchars($prod['fecha_inicio']) . "</td>
            <td>" . htmlspecialchars($prod['fecha_fin']) . "</td>
            <td><span class='badge $badgeColor'>" . htmlspecialchars($prod['nombre_estado']) . "</span></td>
            <td>" . htmlspecialchars($prod['nombre_empleado']) . "</td>
            <td>" . htmlspecialchars($prod['created_at']) . "</td>
            $acciones
        </tr>";
}

$html = $html ?: "<tr><td colspan='8' class='text-center text-muted'>No se encontraron producciones.</td></tr>";

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
