<?php

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



<div id="content" class="container-fluid p-3">
    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-person-vcard-fill me-2"></i> Historial de Compras
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar compras..." id="buscador-compra">
            </div>
            <a href="index.php?vista=registrar_compras" class="btn btn-secondary mb-3"><i class="bi bi-plus"></i> Nueva
                Compra</a>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaRoles" class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Total</th>
                            <th>Material</th>
                            <th>Stock</th>
                            <th>Precio Unitario</th>
                            <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
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
                                    <td>XAF <?= number_format($detalle['precio_unitario'], 2) ?></td>
                                    <td>
                                        <a href="index.php?vista=editar_compras&id=<?= $compra['id'] ?>"
                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>