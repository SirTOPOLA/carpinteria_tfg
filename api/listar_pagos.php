<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require '../config/conexion.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$pagina = isset($_POST['pagina']) ? max(1, intval($_POST['pagina'])) : 1;
$termino = trim($_POST['termino'] ?? '');
$porPagina = 5;
$offset = ($pagina - 1) * $porPagina;

$condicion = '';
$params = [];

if ($termino !== '') {
    $condicion = "WHERE p.id LIKE :busqueda 
        OR f.id LIKE :busqueda 
        OR DATE_FORMAT(p.fecha_pago, '%d/%m/%Y') LIKE :busqueda 
        OR p.metodo_pago LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Total
$totalQuery = $pdo->prepare("
    SELECT COUNT(*) 
    FROM pagos p 
    LEFT JOIN facturas f ON p.factura_id = f.id 
    $condicion
");
$totalQuery->execute($params);
$totalRegistros = $totalQuery->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

 
// Datos
$sql = "
    SELECT 
        p.id, p.factura_id, p.monto_pagado, p.fecha_pago, p.metodo_pago, p.observaciones,
        f.fecha_emision, f.monto_total, f.saldo_pendiente,
        e.nombre AS estado_factura,
        v.nombre_cliente, v.direccion_cliente
    FROM pagos p
    LEFT JOIN facturas f ON p.factura_id = f.id
    LEFT JOIN estados e ON f.estado_id = e.id
    LEFT JOIN ventas v ON f.venta_id = v.id
    $condicion
    ORDER BY p.fecha_pago DESC
    LIMIT $offset, $porPagina
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// HTML
$html = '';
foreach ($pagos as $pago) {
    $fecha = date('d/m/Y', strtotime($pago['fecha_pago']));
    $obs = htmlspecialchars($pago['observaciones'] ?? '—');

    $html .= '
        <tr>
            <td>' . $pago['id'] . '</td>
            <td>' . $pago['factura_id'] . '</td>
            <td>XAF ' . number_format($pago['monto_pagado'], 2) . '</td>
            <td>' . $fecha . '</td>
            <td>' . ucfirst($pago['metodo_pago']) . '</td>
            <td>' . $obs . '</td>
            <td class="text-center">
                <button class="btn btn-outline-info btn-sm" onclick="verPagos(' . $pago['id'] . ')">
                    <i class="bi bi-eye"></i>
                </button>
            </td>
        </tr>
    ';
}


// Paginación
$paginacion = '';
if ($pagina > 1) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link-pagos' data-pagina='" . ($pagina - 1) . "'>&laquo; Anterior</button>";
}

for ($i = max(1, $pagina - 2); $i <= min($totalPaginas, $pagina + 2); $i++) {
    $active = ($i == $pagina) ? 'btn-primary' : 'btn-outline-secondary';
    $paginacion .= "<button class='btn btn-sm $active pagina-link-pagos' data-pagina='$i'>$i</button>";
}

if ($pagina < $totalPaginas) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link-pagos' data-pagina='" . ($pagina + 1) . "'>Siguiente &raquo;</button>";
}

$desde = ($pagina - 1) * $porPagina + 1;
$hasta = min($pagina * $porPagina, $totalRegistros);
$resumen = "Mostrando $desde-$hasta de $totalRegistros pagos";

echo json_encode([
    'success' => true,
    'html' => $html ?: "<tr><td colspan='7' class='text-center text-muted py-3'>No se encontraron pagos.</td></tr>",
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);
