<?php
require_once("../includes/conexion.php");

// ========================
// PARÁMETROS
// ========================
$buscar = trim($_GET['buscar'] ?? '');
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$por_pagina = 10;
$offset = ($pagina - 1) * $por_pagina;

// ========================
// CONSULTA TOTAL
// ========================
$where = '';
$params = [];

if (!empty($buscar)) {
    $where = "WHERE nombre LIKE :buscar";
    $params[':buscar'] = "%$buscar%";
}

$sql_total = "SELECT COUNT(*) FROM categorias_producto $where";
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute($params);
$total = $stmt_total->fetchColumn();
$total_paginas = ceil($total / $por_pagina);

// ========================
// CONSULTA PAGINADA
// ========================
$sql = "SELECT * FROM categorias_producto $where ORDER BY id DESC LIMIT :offset, :limite";
$stmt = $pdo->prepare($sql);

foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, PDO::PARAM_STR);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limite', $por_pagina, PDO::PARAM_INT);
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
// dashboard.php principal
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
include '../includes/conexion.php'; // Asegúrate de tener la conexión a base de datos aquí
?>
 
    <div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center  p-2 mb-3">
        <h4 class="mb-0">Listado de Categorias de productos</h4>
    </div>
    <div class="text-end mb-3">
        <a href="registrar_categorias_producto.php" class="btn btn-success me-2">
            <i class="bi bi-shield-plus"></i> Nueva Categoria
        </a>
        <a href="productos.php" class="btn btn-primary">
            <i class="bi bi-person-lines-fill"></i> Lista de productos
        </a>
    </div>


    <!-- BUSCADOR -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-10 col-8">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar categoría"
                   value="<?= htmlspecialchars($buscar) ?>">
        </div>
        <div class="col-md-2 col-4">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
        </div>
    </form>

    <!-- TABLA -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($categorias) > 0): ?>
                    <?php foreach ($categorias as $cat): ?>
                        <tr>
                            <td><?= $cat['id'] ?></td>
                            <td><?= htmlspecialchars($cat['nombre']) ?></td>
                            <td class="text-center">
                                <a href="editar_categoria_producto.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="eliminar_categoria_producto.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('¿Está seguro de eliminar esta categoría?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No se encontraron categorías.</td>
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
 

<?php include_once("../includes/footer.php"); ?>
