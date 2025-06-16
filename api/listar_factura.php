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

// Condición dinámica para búsqueda (buscamos por cliente, método de pago, fecha factura o monto total)
$condicion = '';
$params = [];

if ($termino !== '') {
    $condicion = "WHERE c.nombre LIKE :busqueda 
        OR v.metodo_pago LIKE :busqueda 
        OR DATE_FORMAT(f.fecha_emision, '%d/%m/%Y') LIKE :busqueda
        OR CAST(f.monto_total AS CHAR) LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Conteo total de facturas
$totalQuery = $pdo->prepare("
    SELECT COUNT(*) 
    FROM facturas f
    INNER JOIN ventas v ON f.venta_id = v.id
    LEFT JOIN clientes c ON v.cliente_id = c.id
    $condicion
");
$totalQuery->execute($params);
$totalRegistros = $totalQuery->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Consulta principal: facturas con datos relacionados
$sql = "
    SELECT 
        f.id,
        f.fecha_emision,
        f.monto_total,
        f.saldo_pendiente,
        e.nombre AS estado_factura,
        v.id AS venta_id,
        v.nombre_cliente,
        c.nombre AS cliente,
        v.metodo_pago,
        v.fecha AS fecha_venta
    FROM facturas f
    INNER JOIN ventas v ON f.venta_id = v.id
    LEFT JOIN clientes c ON v.cliente_id = c.id
    LEFT JOIN estados e ON f.estado_id = e.id AND e.entidad = 'factura'
    $condicion
    ORDER BY f.fecha_emision DESC
    LIMIT $offset, $porPagina
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Construir HTML para la tabla de facturas
$html = '';
foreach ($facturas as $factura) {
    $linkEliminar = ($_SESSION['usuario']['rol'] === 'Administrador') 
        ? "<a href='#' class='btn btn-sm btn-danger btn-eliminar-factura' data-id='{$factura['id']}' title='Eliminar factura'><i class='bi bi-trash-fill'></i></a>"
        : '';

    $fechaEmision = date('d/m/Y', strtotime($factura['fecha_emision']));
    $fechaVenta = date('d/m/Y', strtotime($factura['fecha_venta']));
    $clienteNombre = htmlspecialchars($factura['cliente'] ?: $factura['nombre_cliente']);


    $esPagada = $factura['estado_factura'] === 'pagada';
$estadoClase = $esPagada ? 'btn-success' : 'btn-warning';
$disabled = $esPagada ? 'disabled' : '';
$estado = $factura['estado_factura'] != 'pagada' ? 'pendiente' : 'pagada';
$btnEstado = "
<button 
    class='btn btn-sm $estadoClase btn-registrar-pago' 
    data-id='{$factura['id']}'
    data-total='{$factura['monto_total']}'
    data-pendiente='{$factura['saldo_pendiente']}'
    data-cliente='{$clienteNombre}'
    title='Registrar pago o actualizar estado'
    $disabled>
    
    {$estado}
</button>";
 

    // Visual del saldo pendiente con barra de progreso
    $saldo = floatval($factura['saldo_pendiente']);
    $total = floatval($factura['monto_total']);

    if ($saldo > 0) {
        $color = $saldo > ($total * 0.5) ? 'text-danger' : 'text-warning';
        $barraProgreso = "
        <div class='progress' style='height: 15px;'>
            <div class='progress-bar bg-warning' role='progressbar' style='width: " . (100 - ($saldo / $total * 100)) . "%'></div>
        </div>
        <small class='$color fw-bold'>XAF " . number_format($saldo, 2) . "</small>";
    } else {
        $barraProgreso = "<div class='text-success fw-bold'>Sin deuda</div>";
    }

    $html .= "
    <tr>
        <td>{$factura['id']}</td>
        <td>$clienteNombre</td>
        <td>$fechaVenta</td>
        <td>$fechaEmision</td>
        <td>" . ucfirst($factura['metodo_pago']) . "</td>
        <td>XAF " . number_format($factura['monto_total'], 2) . "</td>
        <td>$barraProgreso</td>
        <td class='text-center'>
            $btnEstado
        </td>
        <td class='text-center'>
            <a href='api/factura.php?id={$factura['id']}' target='_blank' class='btn btn-sm btn-outline-secondary ms-1' title='Imprimir factura'>
                <i class='bi bi-printer'></i>
            </a>
            <a href='index.php?vista=editar_factura&id={$factura['id']}' class='btn btn-sm btn-outline-warning' title='Editar factura'><i class='bi bi-pencil-square'></i></a>
            $linkEliminar
        </td>
    </tr>
    ";
}

// Construcción de la paginación
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

$desde = ($pagina - 1) * $porPagina + 1;
$hasta = min($pagina * $porPagina, $totalRegistros);
$resumen = "Mostrando $desde-$hasta de $totalRegistros resultados";

echo json_encode([
    'success' => true,
    'html' => $html ?: "<tr><td colspan='8' class='text-muted text-center py-3'>No se encontraron facturas.</td></tr>",
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);
