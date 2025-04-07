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
    $where = "WHERE m.nombre LIKE :buscar OR m.descripcion LIKE :buscar";
    $params[':buscar'] = "%$buscar%";
}

// ========================
// TOTAL DE REGISTROS
// ========================
$stmt_total = $pdo->prepare("SELECT COUNT(*) FROM materiales m $where");
$stmt_total->execute($params);
$total = $stmt_total->fetchColumn();
$total_paginas = ceil($total / $por_pagina);

// ========================
// CONSULTA PAGINADA
// ========================
$sql = "SELECT 
    m.id,
    m.nombre,
    m.descripcion,
    m.stock_actual,
    c.nombre AS categoria,
    (
        SELECT dc.precio_unitario 
        FROM detalle_compra dc 
        WHERE dc.material_id = m.id 
        ORDER BY dc.id DESC 
        LIMIT 1
    ) AS precio_unitario
FROM materiales m
LEFT JOIN categorias_materiales c ON m.categoria_id = c.id
$where
ORDER BY m.nombre DESC
LIMIT :offset, :limite";

$stmt = $pdo->prepare($sql);

// Vincular los parámetros de búsqueda
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, PDO::PARAM_STR);
}
// Vincular paginación
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limite', $por_pagina, PDO::PARAM_INT);

// Ejecutar y obtener resultados
$stmt->execute();
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
// dashboard.php principal
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
include '../includes/conexion.php'; // Asegúrate de tener la conexión a base de datos aquí
?>
<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid">

        <div class="">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Materiales Registrados</h4>
                <div>
                    <a href="registrar_material.php" class="btn btn-success" title="Nuevo Material">
                        <i class="bi bi-plus-circle"></i>
                    </a>
                    <a href="categoria_material.php" class="btn btn-primary" title="Ir a la lista de Categoria">
                        <i class="bi bi-tags"></i>
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
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Categoría</th> 
                            <th>stock_actual</th>
                            <th>Precio</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($materiales) > 0): ?>
                            <?php foreach ($materiales as $material): ?>
                                <tr>
                                    <td><?= $material['id'] ?></td>
                                    <td><?= htmlspecialchars($material['nombre']) ?></td>
                                    <td><?= htmlspecialchars($material['descripcion']) ?></td>
                                    <td><?= htmlspecialchars($material['categoria'] ?? 'Sin categoría') ?></td>
                                    
                                    <td><?= number_format($material['stock_actual'], 2) ?></td>
                                    <td>€<?= number_format($material['precio_unitario'], 2) ?></td>

                                    <td class="text-center">
                                        <a href="editar_material.php?id=<?= $material['id'] ?>" class="btn btn-sm btn-warning"
                                            title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="eliminar_material.php?id=<?= $material['id'] ?>" class="btn btn-sm btn-danger"
                                            title="Eliminar"
                                            onclick="return confirm('¿Está seguro de eliminar este material?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">No se encontraron materiales.</td>
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
    </div>

</main>
<?php include_once("../includes/footer.php"); ?>