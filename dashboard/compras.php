<?php
require_once("../includes/conexion.php");

// ========================
// PARÁMETROS
// ========================
// Validar búsqueda y paginación
$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 10;
$inicio = ($pagina > 1) ? ($pagina - 1) * $por_pagina : 0;

// Contar total de registros
$condicion = '';
$params = [];

if ($buscar !== '') {
    $condicion = "WHERE p.nombre LIKE ?";
    $params[] = "%$buscar%";
}

$sql_total = "SELECT COUNT(*) FROM compras c INNER JOIN proveedores p ON c.proveedor_id = p.id $condicion";
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute($params);
$total_registros = $stmt_total->fetchColumn();
$total_paginas = ceil($total_registros / $por_pagina);

// Obtener compras con proveedor
$sql = "
    SELECT c.id, c.fecha, c.total, p.nombre AS proveedor
    FROM compras c
    INNER JOIN proveedores p ON c.proveedor_id = p.id
    $condicion
    ORDER BY c.fecha DESC
    LIMIT $inicio, $por_pagina
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$compras = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Compras Registradas</h4>
        <div>
            <a href="registrar_compra.php" class="btn btn-success" title="Nueva Compra">
                <i class="bi bi-plus-circle"></i>
            </a>
            <a href="materiales.php" class="btn btn-secondary" title="Materiales">
                <i class="bi bi-box-seam"></i>
            </a>
            <a href="categoria_material.php" class="btn btn-primary" title="Categorías">
                <i class="bi bi-tags"></i>
            </a>
        </div>
    </div>

    <!-- BUSCADOR -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-10 col-8">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por descripción o proveedor"
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
                    <th>Proveedor</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($compras) > 0): ?>
                    <?php foreach ($compras as $compra): ?>
                        <tr>
                            <td><?= $compra['id'] ?></td>
                            <td><?= htmlspecialchars($compra['proveedor']) ?></td> 
                            <td><?= number_format($compra['total'], 2) ?></td>
                            <td><?= date("d/m/Y H:i", strtotime($compra['fecha'])) ?></td>
                            <td class="text-center">
                                <a href="editar_compra.php?id=<?= $compra['id'] ?>" class="btn btn-sm btn-warning"
                                    title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="eliminar_compra.php?id=<?= $compra['id'] ?>" class="btn btn-sm btn-danger"
                                    title="Eliminar" onclick="return confirm('¿Está seguro de eliminar esta compra?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <a href="detalle_compra.php?id=<?= $compra['id'] ?>" class="btn btn-sm btn-primary"
                                    title="Detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron compras.</td>
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
</main>
<?php include_once("../includes/footer.php"); ?>