<?php
if (session_status() == PHP_SESSION_NONE) {
    // Si la sesión no está iniciada, se inicia
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
    $condicion = "WHERE p.nombre LIKE :busqueda OR p.unidad LIKE :busqueda";
    $params[':busqueda'] = "%$termino%";
}

// Contar total
$totalQuery = $pdo->prepare("SELECT COUNT(*) FROM productos $condicion");
$totalQuery->execute($params);
$totalRegistros = $totalQuery->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);

// Obtener registros

$sql = "SELECT p.*, i.ruta_imagen AS imagen
        FROM productos p
        LEFT JOIN imagenes_producto i ON p.id = i.producto_id
        $condicion
        ORDER BY p.id DESC
        LIMIT $offset, $porPagina";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Renderizar tabla 
$html = '';

foreach ($productos as $producto) {
    // Botón eliminar solo para administradores
    $link = ($_SESSION['usuario']['rol'] === 'Administrador') 
        ? "<a href='#' class='btn btn-sm btn-danger btn-eliminar' data-id='{$producto['id']}'><i class='bi bi-trash-fill'></i></a>"
        : '';

    // Mostrar imagen si existe
    if (!empty($producto['imagen']) && file_exists($producto['imagen'])) {
        $rutaImagen ="api/". $producto['imagen'];
        $img = "
            <img src='$rutaImagen' 
                 class='img-thumbnail img-modal-trigger'
                 data-src='$rutaImagen'
                 style='width: 60px; height: 60px; object-fit: cover; cursor: pointer;'>
        ";
    } else {
        $img = "<span class='text-muted'>Sin imagen</span>";
    }

    // Agregar fila a la tabla
    $html .= "
        <tr>
            <td>{$producto['id']}</td>
            <td>$img</td>
            <td>" . htmlspecialchars($producto['nombre']) . "</td>
            <td>" . htmlspecialchars($producto['descripcion']) . "</td>
            <td>" . htmlspecialchars($producto['stock']) . "</td>
            <td>XAF " . number_format($producto['precio_unitario'], 2) . "</td>
            <td class='text-center'>
                <a href='index.php?vista=editar_productos&id={$producto['id']}' class='btn btn-sm btn-outline-warning'>
                    <i class='bi bi-pencil-square'></i>
                </a>
                $link
            </td>
        </tr>
    ";
}


$paginacion = '';

// Botón anterior
if ($pagina > 1) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link' data-pagina='" . ($pagina - 1) . "'>&laquo; Anterior</button>";
}

// Rango de páginas visibles
$rango = 2; // cantidad de páginas a mostrar antes y después de la actual
$inicio = max(1, $pagina - $rango);
$fin = min($totalPaginas, $pagina + $rango);

for ($i = $inicio; $i <= $fin; $i++) {
    $active = $i === $pagina ? 'btn-primary' : 'btn-outline-secondary';
    $paginacion .= "<button class='btn btn-sm $active pagina-link' data-pagina='$i'>$i</button>";
}

// Botón siguiente
if ($pagina < $totalPaginas) {
    $paginacion .= "<button class='btn btn-sm btn-outline-secondary pagina-link' data-pagina='" . ($pagina + 1) . "'>Siguiente &raquo;</button>";
}

//calculamos los valores de inicio y fin
$desde = ($pagina - 1) * $porPagina + 1;
$hasta = min($pagina * $porPagina, $totalRegistros);
$resumen = "Mostrando $desde-$hasta de $totalRegistros resultados";


echo json_encode([
    'success'=> true,
    'html' => $html ?: "<tr><td colspan='6' class='text-muted text-center py-3'>No se encontraron productos.</td></tr>",
    'paginacion' => $paginacion,
    'resumen' => $resumen
]);
