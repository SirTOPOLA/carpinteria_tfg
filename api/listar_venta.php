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

// Construcción de condición dinámica
$condicion = '';
$params = [];

if ($termino !== '') {
    $condicion = "WHERE c.nombre LIKE :busqueda OR v.metodo_pago LIKE :busqueda OR DATE_FORMAT(v.fecha, '%d/%m/%Y') LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Conteo total
$totalQuery = $pdo->prepare("SELECT COUNT(*) FROM ventas v LEFT JOIN clientes c ON v.cliente_id = c.id $condicion");
$totalQuery->execute($params);
$totalRegistros = $totalQuery->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Obtener ventas
$sql = "
    SELECT v.id, c.nombre AS cliente, v.fecha, v.metodo_pago, v.total
    FROM ventas v
    LEFT JOIN clientes c ON v.cliente_id = c.id
    $condicion
    ORDER BY v.fecha DESC
    LIMIT $offset, $porPagina
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Construir HTML
$html = '';
foreach ($ventas as $venta) {
     // Botón eliminar solo para administradores
     $link = ($_SESSION['usuario']['rol'] === 'Administrador') 
     ? "<a href='#' class='btn btn-sm btn-danger btn-eliminar' data-id='{$venta['id']}'><i class='bi bi-trash-fill'></i></a>"
     : '';
    $fechaFormateada = date('d/m/Y', strtotime($venta['fecha']));
    $html .= "
    <tr>
        <td>{$venta['id']}</td>
        <td>" . htmlspecialchars($venta['cliente']) . "</td>
        <td>$fechaFormateada</td>
        <td>" . ucfirst($venta['metodo_pago']) . "</td>
        <td>$" . number_format($venta['total'], 2) . "</td>
        <td class='text-center'>
             
            <button class='btn btn-sm btn-outline-primary btn-toggle' data-id='{$venta['id']}' aria-expanded='false' title='Ver detalles'>
                <i class='bi bi-eye'></i>
            </button>
 
            <a href='api/factura.php?id={$venta['id']}' target='_blank' class='btn btn-sm btn-outline-secondary ms-1' title='Imprimir factura'>
                <i class='bi bi-printer'></i>
            </a>
            <a href='index.php?vista=editar_ventas&id={$venta['id']}' class='btn btn-sm btn-outline-warning'><i class='bi bi-pencil-square'></i></a>
            $link
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

$desde = ($pagina - 1) * $porPagina + 1;
$hasta = min($pagina * $porPagina, $totalRegistros);
$resumen = "Mostrando $desde-$hasta de $totalRegistros resultados";

echo json_encode([
    'success' => true,
    'html' => $html ?: "<tr><td colspan='6' class='text-muted text-center py-3'>No se encontraron ventas.</td></tr>",
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);



