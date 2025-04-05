<?php
require_once("../includes/conexion.php");

// ========================
// PARÁMETROS
// ========================
$buscar = trim($_GET['buscar'] ?? '');
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$por_pagina = 10;
$offset = ($pagina - 1) * $por_pagina;

$where = '';
$params = [];

if (!empty($buscar)) {
    $where = "WHERE p.nombre LIKE :buscar OR p.descripcion LIKE :buscar";
    $params[':buscar'] = "%$buscar%";
}

// ========================
// TOTAL DE REGISTROS
// ========================
$stmt_total = $pdo->prepare("SELECT COUNT(*) FROM productos p $where");
$stmt_total->execute($params);
$total = $stmt_total->fetchColumn();
$total_paginas = ceil($total / $por_pagina);

// ========================
// CONSULTA PAGINADA
// ========================
$sql = "
    SELECT p.id, p.nombre, p.descripcion, p.precio, p.fecha_creacion, c.nombre AS categoria,
           (SELECT ruta_imagen FROM imagenes_producto i WHERE i.producto_id = p.id LIMIT 1) AS imagen
    FROM productos p
    LEFT JOIN categorias_producto c ON p.categoria_id = c.id
    $where
    ORDER BY p.fecha_creacion DESC
    LIMIT :offset, :limite
";
$stmt = $pdo->prepare($sql);

foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, PDO::PARAM_STR);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limite', $por_pagina, PDO::PARAM_INT);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once("../includes/header.php"); ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Productos Registrados</h4>
        <div>
            <a href="registrar_producto.php" class="btn btn-success" title="Nuevo Producto">
                <i class="bi bi-plus-circle"></i>
            </a>
            <a href="categorias.php" class="btn btn-primary" title="Categorías">
                <i class="bi bi-tags"></i>
            </a>
            <a href="imagen_producto.php" class="btn btn-secondary" title="Imágenes">
                <i class="bi bi-images"></i>
            </a>
        </div>
    </div>

    <!-- BUSCADOR -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-10 col-8">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o descripción"
                   value="<?= htmlspecialchars($buscar) ?>">
        </div>
        <div class="col-md-2 col-4">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>

    <!-- TABLA -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Fecha</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($productos) > 0): ?>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= $producto['id'] ?></td>
                            <td class="text-center">
                                <?php if (!empty($producto['imagen']) && file_exists("../uploads/" . $producto['imagen'])): ?>
                                    <img src="../uploads/<?= $producto['imagen'] ?>"
                                         class="img-thumbnail img-modal-trigger"
                                         data-src="../uploads/<?= $producto['imagen'] ?>"
                                         style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;">
                                <?php else: ?>
                                    <span class="text-muted">Sin imagen</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($producto['nombre']) ?></td>
                            <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                            <td><?= htmlspecialchars($producto['categoria'] ?? 'Sin categoría') ?></td>
                            <td>€<?= number_format($producto['precio'], 2) ?></td>
                            <td><?= date("d/m/Y H:i", strtotime($producto['fecha_creacion'])) ?></td>
                            <td class="text-center">
                                <a href="editar_producto.php?id=<?= $producto['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="eliminar_producto.php?id=<?= $producto['id'] ?>" class="btn btn-sm btn-danger" title="Eliminar"
                                   onclick="return confirm('¿Está seguro de eliminar este producto?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No se encontraron productos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINACIÓN -->
    <?php if ($total_paginas > 1): ?>
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                        <a class="page-link" href="?buscar=<?= urlencode($buscar) ?>&pagina=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- MODAL DE IMAGEN -->
<div class="modal fade" id="modalImagen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark">
            <div class="modal-body text-center p-0">
                <img id="imagenAmpliada" src="" class="img-fluid rounded" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const triggers = document.querySelectorAll(".img-modal-trigger");
        const modalImg = document.getElementById("imagenAmpliada");

        triggers.forEach(img => {
            img.addEventListener("click", function () {
                const src = this.getAttribute("data-src");
                modalImg.src = src;
                const modal = new bootstrap.Modal(document.getElementById('modalImagen'));
                modal.show();
            });
        });
    });
</script>

<?php include_once("../includes/footer.php"); ?>
