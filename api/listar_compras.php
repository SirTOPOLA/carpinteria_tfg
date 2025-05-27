<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require '../config/conexion.php';
header('Content-Type: application/json');

$pagina = isset($_POST['pagina']) ? max(1, intval($_POST['pagina'])) : 1;
$termino = trim($_POST['termino'] ?? '');
$porPagina = 5;
$offset = ($pagina - 1) * $porPagina;

$condicion = '';
$params = [];

if ($termino !== '') {
    $condicion = "WHERE p.nombre LIKE :busqueda OR c.codigo LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Contar total de compras (sin duplicar por detalles)
$totalQuery = $pdo->prepare("SELECT COUNT(DISTINCT c.id) FROM compras c LEFT JOIN proveedores p ON c.proveedor_id = p.id $condicion");
$totalQuery->execute($params);
$totalRegistros = $totalQuery->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Consulta de registros con JOIN a detalles y materiales
$sql = "SELECT c.id AS compra_id, c.fecha, c.total, c.codigo,
               p.nombre AS proveedor,
               m.nombre AS material,
               dc.cantidad,
               dc.precio_unitario
        FROM compras c
        LEFT JOIN proveedores p ON c.proveedor_id = p.id
        LEFT JOIN detalles_compra dc ON dc.compra_id = c.id
        LEFT JOIN materiales m ON m.id = dc.material_id
        $condicion
        ORDER BY c.id DESC
        LIMIT $offset, $porPagina";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organizar por compra_id
$agrupadas = [];
foreach ($filas as $fila) {
    $id = $fila['compra_id'];
    if (!isset($agrupadas[$id])) {
        $agrupadas[$id] = [
            'codigo' => $fila['codigo'],
            'fecha' => $fila['fecha'],
            'total' => $fila['total'],
            'proveedor' => $fila['proveedor'],
            'detalles' => []
        ];
    }
    if ($fila['material']) {
        $agrupadas[$id]['detalles'][] = [
            'material' => $fila['material'],
            'cantidad' => $fila['cantidad'],
            'precio_unitario' => $fila['precio_unitario']
        ];
    }
}

// Render HTML
$html = '';
foreach ($agrupadas as $id => $compra) {
    $rowspan = max(1, count($compra['detalles']));
    $primera = true;

    foreach ($compra['detalles'] ?: [[]] as $detalle) {
        $html .= "<tr>";

        if ($primera) {
            $link = ($_SESSION['usuario']['rol'] === 'Administrador') 
                ? "<a href='#' class='btn btn-sm btn-danger btn-eliminar' data-id='$id'><i class='bi bi-trash-fill'></i></a>"
                : '';

            $html .= "
                <td rowspan='$rowspan'>$id</td>
                <td rowspan='$rowspan'>" . htmlspecialchars($compra['codigo']) . "</td>
                <td rowspan='$rowspan'>" . htmlspecialchars($compra['fecha']) . "</td>
                <td rowspan='$rowspan'>" . htmlspecialchars($compra['proveedor']) . "</td>
                <td rowspan='$rowspan'>XAF " . number_format($compra['total'], 0) . "</td>
            ";
        }

        $html .= isset($detalle['material']) ? "
            <td>" . htmlspecialchars($detalle['material']) . "</td>
            <td>" . intval($detalle['cantidad']) . "</td>
            <td>XAF " . number_format($detalle['precio_unitario'], 2) . "</td>
        " : "<td colspan='3' class='text-muted text-center'>Sin detalles</td>";

        if ($primera) {
            $html .= "
                <td rowspan='$rowspan' class='text-center'>
                    <a href='index.php?vista=editar_compras&id=$id' class='btn btn-sm btn-outline-warning'><i class='bi bi-pencil-square'></i></a>
                    $link
                </td>
            ";
            $primera = false; // Solo después de colocar acciones
        }

        $html .= "</tr>";
    }
}

// Paginación
$paginacion = '';
if ($pagina > 1) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link' data-pagina='" . ($pagina - 1) . "'>&laquo; Anterior</button>";
}
for ($i = max(1, $pagina - 2); $i <= min($totalPaginas, $pagina + 2); $i++) {
    $active = $i === $pagina ? 'btn-primary' : 'btn-outline-secondary';
    $paginacion .= "<button class='btn btn-sm $active pagina-link' data-pagina='$i'>$i</button>";
}
if ($pagina < $totalPaginas) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link' data-pagina='" . ($pagina + 1) . "'>Siguiente &raquo;</button>";
}

$desde = ($pagina - 1) * $porPagina + 1;
$hasta = min($pagina * $porPagina, $totalRegistros);
$resumen = "Mostrando $desde-$hasta de $totalRegistros resultados";

// Respuesta JSON
echo json_encode([
    'success'=> true,
    'html' => $html ?: "<tr><td colspan='9' class='text-muted text-center py-3'>No se encontraron compras.</td></tr>",
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);
