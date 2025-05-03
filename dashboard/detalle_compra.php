<?php
require_once("../includes/conexion.php");


// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de compra inválido.</div>";
    exit;
}

$compra_id = (int) $_GET['id'];

try {
    // Info de la compra
    $sqlcompra = "SELECT p.*, p.nombre AS proveedores, c.direccion, c.telefono, c.correo
                 FROM proveedores v
                 JOIN materiales c ON v.proveedores = c.id
                 WHERE v.id = :id";
    $stmtcompra = $pdo->prepare($sqlcompra);
    $stmtcompra->execute([':id' => $compra_id]);
    $compra = $stmtcompra->fetch(PDO::FETCH_ASSOC);

    if (!$compra) {
        echo "<div class='alert alert-danger'>compra no encontrada.</div>";
        exit;
    }

    // Detalles de los ítems
    $sqlItems = "SELECT vd.*, 
                    CASE 
                        WHEN vd.tipo_item = 'producto' THEN (SELECT nombre FROM productos WHERE id = vd.item_id)
                        WHEN vd.tipo_item = 'proyecto' THEN (SELECT nombre FROM proyectos WHERE id = vd.item_id)
                        WHEN vd.tipo_item = 'servicio' THEN (SELECT nombre FROM servicios WHERE id = vd.item_id)
                        ELSE 'Ítem desconocido'
                    END AS nombre_item
                 FROM compra_detalle vd
                 WHERE vd.compra_id = :compra_id";
    $stmtItems = $pdo->prepare($sqlItems);
    $stmtItems->execute([':compra_id' => $compra_id]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error al cargar los datos: " . $e->getMessage() . "</div>";
    exit;
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-4">
    <div class="container col-sm-12 col-md-9 col-xl-8">
        <div class="border-bottom pb-3 mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Carpintería Profesional </h2>
                <small>Peres Merka-Mar, Ciudad Malabo</small><br>
                <small>Email: info@carpinteria.com | Tel: 555-123-456</small>
            </div>
            <div class="text-end">
                <h4 class="mb-1">Factura #<?= $compra['id'] ?></h4>
                <div><strong>Fecha:</strong> <?= htmlspecialchars($compra['fecha']) ?></div>
            </div>
        </div>

        <!-- DATOS CLIENTE -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Cliente</h5>
                <p class="mb-0"><strong><?= htmlspecialchars($compra['cliente']) ?></strong></p>
                <p class="mb-0"><?= nl2br(htmlspecialchars($compra['direccion'])) ?></p>
                <p class="mb-0"><?= htmlspecialchars($compra['correo']) ?></p>
                <p><?= htmlspecialchars($compra['telefono']) ?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <h5>Condiciones</h5>
                <p class="mb-0"><strong>Tipo de pago:</strong> <?= ucfirst($compra['tipo_pago']) ?></p>
                <p class="mb-0"><strong>Emitido por:</strong> Sistema Carpintería</p>
            </div>
        </div>

        <!-- TABLA DETALLE -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th class="text-end">Cantidad</th>
                        <th class="text-end">Precio unitario (XAF)</th>
                        <th class="text-end">Subtotal (XAF)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    $total = 0;
                    foreach ($items as $item):
                        $subtotal = $item['cantidad'] * $item['precio_unitario'];
                        $total += $subtotal;
                        ?>
                        <tr>
                            <td><?= $n++ ?></td>
                            <td><?= ucfirst($item['tipo_item']) ?></td>
                            <td><?= htmlspecialchars($item['nombre_item']) ?></td>
                            <td class="text-end"><?= $item['cantidad'] ?></td>
                            <td class="text-end"><?= number_format($item['precio_unitario'], 2) ?></td>
                            <td class="text-end"><?= number_format($subtotal, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Total:</strong></td>
                        <td class="text-end"><strong><?= number_format($total, 2) ?> €</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- PIE -->
        <p class="text-center text-muted">Gracias por su compra. Esta factura ha sido generada automáticamente.</p>

        
        <div class="text-center mt-4">
            <a href="compras.php" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Imprimir
            </button>
        </div>

    </div>

</main>

<?php include '../includes/footer.php'; ?>