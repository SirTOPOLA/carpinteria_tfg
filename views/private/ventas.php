<?php


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
    $where = "WHERE v.cliente_id LIKE :buscar";
    $params[':buscar'] = "%$buscar%";
}

// ========================
// TOTAL DE REGISTROS
// ========================
$stmt_total = $pdo->prepare("SELECT COUNT(*) FROM ventas v $where");
$stmt_total->execute($params);
$total = $stmt_total->fetchColumn();
$total_paginas = ceil($total / $por_pagina);

// ========================
// CONSULTA PAGINADA
// ========================
$sql = "
    SELECT v.id, v.cliente_id, v.fecha, v.total
    FROM ventas v
    $where
    ORDER BY v.fecha DESC
    LIMIT :offset, :limite
";
$stmt = $pdo->prepare($sql);

foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, PDO::PARAM_STR);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limite', $por_pagina, PDO::PARAM_INT);
$stmt->execute();
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<?php



// Obtener ventas con nombre del cliente
$sql = "SELECT v.id, v.fecha, v.tipo_pago, v.total, c.nombre AS cliente
        FROM ventas v
        JOIN clientes c ON v.cliente_id = c.id
        ORDER BY v.fecha DESC";
$stmt = $pdo->query($sql);
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="content" class="container-fluid py-4">
    
    
    <?php if (count($ventas) > 0): ?>
        <!-- Tarjeta contenedora -->
        <div class="card mb-4">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <h4 class="fw-bold mb-0 text-white">
                    <i class="bi bi-receipt-cutoff me-2"></i> Lista de Ventas
                </h4>
                <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="buscador-ventas" placeholder="Buscar cliente...">
                </div>
                <a href="registrar_ventas.php" class="btn btn-secondary">
                    <i class="bi bi-plus-circle me-1"></i> Nueva Venta
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle table-custom mb-0">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash me-1"></i>ID</th>
                                <th><i class="bi bi-person-fill me-1"></i>Cliente</th>
                                <th><i class="bi bi-calendar me-1"></i>Fecha</th>
                                <th><i class="bi bi-cash-coin me-1"></i>Tipo de Pago</th>
                                <th><i class="bi bi-currency-euro me-1"></i>Total</th>
                                <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ventas as $venta): ?>
                                <tr>
                                    <td><?= $venta['id'] ?></td>
                                    <td><?= htmlspecialchars($venta['cliente']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($venta['fecha'])) ?></td>
                                    <td><?= ucfirst($venta['tipo_pago']) ?></td>
                                    <td><?= number_format($venta['total'], 2) ?></td>
                                    <td>
                                        <a href="detalles_ventas.php?id=<?= $venta['id'] ?>"
                                            class="btn btn-sm btn-outline-info mb-1" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="editar_venta.php?id=<?= $venta['id'] ?>"
                                            class="btn btn-sm btn-outline-warning mb-1" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="eliminar_venta.php?id=<?= $venta['id'] ?>"
                                            class="btn btn-sm btn-outline-danger mb-1" title="Eliminar"
                                            onclick="return confirm('¿Estás seguro de eliminar esta venta? Esta acción no se puede deshacer.')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>No hay ventas registradas aún.
        </div>
    <?php endif; ?>
</div>