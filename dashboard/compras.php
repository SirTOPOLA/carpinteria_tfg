<?php
require_once("../includes/conexion.php");


// Parámetros de búsqueda
$busqueda = isset($_GET["busqueda"]) ? trim($_GET["busqueda"]) : "";

// Paginación
$por_pagina = 10;
$pagina = isset($_GET["pagina"]) && is_numeric($_GET["pagina"]) ? (int)$_GET["pagina"] : 1;
$inicio = ($pagina - 1) * $por_pagina;

// Contar total de compras
$sql_total = "SELECT COUNT(*) FROM compras c INNER JOIN proveedores p ON c.proveedor_id = p.id";
$params = [];

if (!empty($busqueda)) {
    $sql_total .= " WHERE p.nombre LIKE ?";
    $params[] = "%$busqueda%";
}

$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute($params);
$total_registros = $stmt_total->fetchColumn();
$total_paginas = ceil($total_registros / $por_pagina);

// Obtener compras
$sql = "SELECT c.*, p.nombre AS proveedor_nombre, c.fecha 
        FROM compras c
        INNER JOIN proveedores p ON c.proveedor_id = p.id";

if (!empty($busqueda)) {
    $sql .= " WHERE p.nombre LIKE ?";
}

$sql .= " ORDER BY c.fecha DESC LIMIT $inicio, $por_pagina";
$stmt = $pdo->prepare($sql);
if (!empty($busqueda)) {
    $stmt->execute(["%$busqueda%"]);
} else {
    $stmt->execute();
}

$compras = $stmt->fetchAll();
?>

<?php include_once("../includes/header.php"); ?> 
<div class="container mt-4">
    <!-- BARRA DE ACCIONES -->
    <div class="d-flex justify-content-between align-items-center  p-2 mb-3">
        <h4 class="mb-0">Listado de Roles</h4>
        <div>
            <a href="registrar_compra.php" class="btn btn-success me-2">
                <i class="bi bi-shield-plus"></i> Nuevo Compras
            </a>
            <a href="compras.php" class="btn btn-primary">
                <i class="bi bi-person-lines-fill"></i> Lista de Compras
            </a>
        </div>
    </div>

    <!-- BUSCADOR -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-10 col-8">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar rol por nombre"
                   value="<?= htmlspecialchars($busqueda) ?>">
        </div>
        <div class="col-md-2 col-4">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Proveedor</th>
                <th>Total</th>
                <th>Fecha</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($compras) > 0): ?>
                <?php foreach ($compras as $compra): ?>
                    <tr>
                        <td><?= $compra["id"] ?></td>
                        <td><?= htmlspecialchars($compra["proveedor_nombre"]) ?></td>
                        <td>$<?= number_format($compra["total"], 2) ?></td>
                        <td><?= date("d/m/Y H:i", strtotime($compra["fecha"])) ?></td>
                        <td><a href="ver_compra.php?id=<?= $compra["id"] ?>" class="btn btn-sm btn-info">Ver Detalle</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">No se encontraron compras.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>&busqueda=<?= urlencode($busqueda) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <?php include_once("../includes/footer.php"); ?>
