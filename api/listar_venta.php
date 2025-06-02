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

// Condición dinámica
$filtro = '';
$params = [];

if ($termino !== '') {
    $filtro = "WHERE c.nombre LIKE :busqueda OR v.metodo_pago LIKE :busqueda OR DATE_FORMAT(v.fecha, '%d/%m/%Y') LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Query principal con agrupamiento
$sql = "
    SELECT 
        v.id,
        v.nombre_cliente,
        c.nombre AS cliente,
        v.fecha,
        v.metodo_pago,
        v.total,
        SUM(dv.subtotal) AS subtotal,
        SUM(dv.descuento) AS descuento,
        f.id AS factura_id,
        f.estado_id,
        e.nombre AS estado_factura,
        COUNT(*) OVER() AS total_registros
    FROM ventas v
    LEFT JOIN clientes c ON v.cliente_id = c.id
    LEFT JOIN detalles_venta dv ON dv.venta_id = v.id
    LEFT JOIN facturas f ON f.venta_id = v.id
    LEFT JOIN estados e ON e.id = f.estado_id AND e.entidad = 'factura'
    $filtro
    GROUP BY v.id
    ORDER BY v.fecha DESC
    LIMIT :offset, :porPagina
";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
$stmt->execute();
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total de registros
$totalRegistros = $ventas[0]['total_registros'] ?? 0;
$totalPaginas = ceil($totalRegistros / $porPagina);

// Generar HTML
$html = '';
foreach ($ventas as $venta) {
    $linkEliminar = ($_SESSION['usuario']['rol'] === 'Administrador')
        ? "<a href='#' class='btn btn-sm btn-danger btn-eliminar' data-id='{$venta['id']}' title='Eliminar'><i class='bi bi-trash-fill'></i></a>"
        : '';

    $fecha = date('d/m/Y', strtotime($venta['fecha']));
    $cliente = $venta['cliente'] ?: $venta['nombre_cliente'];

    $facturaEmitida = !empty($venta['factura_id']);
    $estadoClase = $facturaEmitida ? 'btn-success' : 'btn-danger';
    $icono = $facturaEmitida ? 'bi-toggle-on' : 'bi-toggle-off';
    $texto = $facturaEmitida ? 'Emitida' : 'Emitir';

    if ($facturaEmitida && $venta['estado_factura']) {
        $texto .= " ({$venta['estado_factura']})";
    }

    $btnFactura = $facturaEmitida
        ? "<button class='btn btn-sm $estadoClase disabled' title='Factura emitida'><i class='bi $icono'></i> $texto</button>"
        : "<button class='btn btn-sm $estadoClase btn-emitir-factura' data-id='{$venta['id']}' title='Emitir factura'><i class='bi $icono'></i> $texto</button>";

    $html .= "
    <tr>
        <td>{$venta['id']}</td>
        <td>" . htmlspecialchars($cliente) . "</td>
        <td>$fecha</td>
        <td>" . ucfirst($venta['metodo_pago']) . "</td>
        <td>XAF " . number_format($venta['total'], 2) . "</td>
        <td>" . rtrim(rtrim(number_format($venta['descuento'], 1, '.', ''), '0'), '.') . " %</td>
        <td>XAF " . number_format($venta['subtotal'], 2) . "</td>
        <td class='text-center'>
            <button class='btn btn-sm btn-outline-primary btn-toggle' data-id='{$venta['id']}' title='Ver detalles'><i class='bi bi-eye'></i></button>
            $btnFactura
            <a href='api/factura.php?id={$venta['id']}' target='_blank' class='btn btn-sm btn-outline-secondary ms-1' title='Imprimir factura'>
                <i class='bi bi-printer'></i>
            </a>
            <a href='index.php?vista=editar_ventas&id={$venta['id']}' class='btn btn-sm btn-outline-warning' title='Editar'><i class='bi bi-pencil-square'></i></a>
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
    $active = ($i === $pagina) ? 'btn-primary' : 'btn-outline-secondary';
    $paginacion .= "<button class='btn btn-sm $active pagina-link' data-pagina='$i'>$i</button>";
}

if ($pagina < $totalPaginas) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link' data-pagina='" . ($pagina + 1) . "'>Siguiente &raquo;</button>";
}

$desde = $offset + 1;
$hasta = min($offset + $porPagina, $totalRegistros);
$resumen = "Mostrando $desde-$hasta de $totalRegistros resultados";

echo json_encode([
    'success' => true,
    'html' => $html ?: "<tr><td colspan='8' class='text-muted text-center py-3'>No se encontraron ventas.</td></tr>",
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);
