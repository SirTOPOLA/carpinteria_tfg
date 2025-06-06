<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
require '../config/conexion.php';

$response = [
    'success' => false,
    'html' => '',
    'paginacion' => '',
    'resumen' => '',
    'error' => ''
];

try {
    if (!isset($pdo)) {
        throw new Exception('Error de conexión a la base de datos.');
    }

    // Validación de entrada
    $pagina = isset($_POST['pagina']) ? max(1, intval($_POST['pagina'])) : 1;
    $termino = trim($_POST['termino'] ?? '');
    $porPagina = 5;
    $offset = ($pagina - 1) * $porPagina;

    // Condición y parámetros
    $condicion = '';
    $paramsBusqueda = [];

    if ($termino !== '') {
        $condicion = "WHERE nombre LIKE :busqueda OR apellido LIKE :busqueda OR email LIKE :busqueda OR codigo LIKE :busqueda";
        $paramsBusqueda[':busqueda'] = "%$termino%";
    }

    // Total de registros
    $sqlTotal = "SELECT COUNT(*) FROM empleados $condicion";
    $stmtTotal = $pdo->prepare($sqlTotal);
    if (!empty($paramsBusqueda)) {
        $stmtTotal->execute($paramsBusqueda);
    } else {
        $stmtTotal->execute();
    }
    $totalRegistros = $stmtTotal->fetchColumn();

    if ($totalRegistros === false) {
        throw new Exception('Error al contar los registros.');
    }

    $totalPaginas = ceil($totalRegistros / $porPagina);

    // Registros paginados
    $sql = "SELECT * FROM empleados $condicion ORDER BY nombre ASC LIMIT :offset, :limite";
    $stmt = $pdo->prepare($sql);

    // Solo bind de búsqueda si es necesario
    if (!empty($paramsBusqueda)) {
        foreach ($paramsBusqueda as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
    }

    // bind offset y limite
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->bindValue(':limite', (int)$porPagina, PDO::PARAM_INT);
    $stmt->execute();

    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Render HTML
    $html = '';
    foreach ($empleados as $e) {
        $nombreCompleto = htmlspecialchars($e['nombre'] . ' ' . $e['apellido']);
        $salario = htmlspecialchars($e['salario'] ?? 'Sin definir');
        $fecha = date('d/m/Y', strtotime($e['fecha_ingreso'] ?? '0000-00-00'));

        $botonEliminar = '';
        if (isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'Administrador') {
            $botonEliminar = "<a href='#' class='btn btn-sm btn-danger btn-eliminar' data-id='{$e['id']}'><i class='bi bi-trash'></i></a>";
        }

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

    // Respuesta
    $response['success'] = true;
    $response['html'] = $html;
    $response['paginacion'] = $paginacion;
    $response['resumen'] = $resumen;

} catch (PDOException $e) {
    $response['error'] = 'Error de base de datos: ' . $e->getMessage();
} catch (Exception $e) {
    $response['error'] = 'Error general: ' . $e->getMessage();
}

echo json_encode($response);
