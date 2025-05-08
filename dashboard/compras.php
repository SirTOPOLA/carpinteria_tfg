<?php
require_once '../includes/conexion.php';

try {
    // Obtener todas las compras
    $stmt = $pdo->query("
        SELECT c.id, c.fecha, c.total, p.nombre AS proveedor
        FROM compras c
        LEFT JOIN proveedores p ON c.proveedor_id = p.id
        ORDER BY c.fecha DESC
    ");
    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener detalles de materiales por compra
    $stmt = $pdo->query("
        SELECT dc.compra_id, m.nombre AS material, dc.cantidad, dc.precio_unitario, m.stock_minimo AS stock_minimo
        FROM detalles_compra dc
        INNER JOIN materiales m ON dc.material_id = m.id
    ");
    $detallesPorCompra = [];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $det) {
        $detallesPorCompra[$det['compra_id']][] = $det;
    }
} catch (PDOException $e) {
    die("Error al cargar compras: " . htmlspecialchars($e->getMessage()));
}
?>

<?php
// dashboard.php principal
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
?>
<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid p-3">
        <h2 class="mb-3">Historial de Compras</h2>
        <a href="registrar_compra.php" class="btn btn-primary mb-3">Nueva Compra</a>

        <?php if (empty($compras)): ?>
            <div class="alert alert-warning">No se han registrado compras.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th># Compra</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Total</th>
                            <th>Material</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Stock Mínimo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($compras as $compra): ?>
                            <?php foreach ($detallesPorCompra[$compra['id']] ?? [] as $detalle): ?>
                                <tr>
                                    <td><?= $compra['id'] ?></td>
                                    <td><?= $compra['fecha'] ?></td>
                                    <td><?= htmlspecialchars($compra['proveedor']) ?></td>
                                    <td>$<?= number_format($compra['total'], 2) ?></td>
                                    <td><?= htmlspecialchars($detalle['material']) ?></td>
                                    <td><?= $detalle['cantidad'] ?></td>
                                    <td>$<?= number_format($detalle['precio_unitario'], 2) ?></td>
                                    <td><?= $detalle['stock_minimo'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>