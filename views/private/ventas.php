<?php
session_start();
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

include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';

// Obtener ventas con nombre del cliente
$sql = "SELECT v.id, v.fecha, v.tipo_pago, v.total, c.nombre AS cliente
        FROM ventas v
        JOIN clientes c ON v.cliente_id = c.id
        ORDER BY v.fecha DESC";
$stmt = $pdo->query($sql);
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid py-4">
        
        <h4 class="mb-4"><i class="bi bi-cart-fill me-1"></i> Listado de Ventas</h4>

        <?php if (isset($_GET['exito'])): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-1"></i> Venta registrada correctamente.
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <a href="registrar_ventas.php" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nueva Venta
            </a>
        </div>

        <?php if (count($ventas) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Tipo de Pago</th>
                            <th>Total (€)</th>
                            <th>Acciones</th>
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

                                    <a href="detalles_ventas.php?id=<?= $venta['id'] ?>" class="btn btn-sm btn-info mb-1">
                                        <i class="bi bi-eye"></i>  
                                    </a>
                                    <a href="editar_venta.php?id=<?= $venta['id'] ?>" class="btn btn-sm btn-warning mb-1">
                                        <i class="bi bi-pencil"></i>  
                                    </a>
                                    <a href="eliminar_venta.php?id=<?= $venta['id'] ?>" class="btn btn-sm btn-danger mb-1"
                                        onclick="return confirm('¿Estás seguro de eliminar esta venta? Esta acción no se puede deshacer.')">
                                        <i class="bi bi-trash"></i>  
                                    </a>


                                    <!-- Puedes agregar botones de editar/eliminar si deseas -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                No hay ventas registradas aún.
            </div>
        <?php endif; ?>
    </div>
 

<?php include '../includes/footer.php'; ?>