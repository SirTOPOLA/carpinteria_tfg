<?php
require_once("../includes/conexion.php");


// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de venta inválido.</div>";
    exit;
}

$venta_id = (int) $_GET['id'];

try {
    // Info de la venta
    $sqlVenta = "SELECT v.*, c.nombre AS cliente, c.direccion, c.telefono, c.correo
                 FROM ventas v
                 JOIN clientes c ON v.cliente_id = c.id
                 WHERE v.id = :id";
    $stmtVenta = $pdo->prepare($sqlVenta);
    $stmtVenta->execute([':id' => $venta_id]);
    $venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

    if (!$venta) {
        echo "<div class='alert alert-danger'>Venta no encontrada.</div>";
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
                 FROM venta_detalle vd
                 WHERE vd.venta_id = :venta_id";
    $stmtItems = $pdo->prepare($sqlItems);
    $stmtItems->execute([':venta_id' => $venta_id]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error al cargar los datos: " . $e->getMessage() . "</div>";
    exit;
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>
   <!-- Contenido -->
   <div class="container-fluid py-4">
    <div class="container col-sm-12 col-md-9 col-xl-8">
        <div class="border-bottom pb-3 mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Carpintería Profesional </h2>
                <small>Peres Merka-Mar, Ciudad Malabo</small><br>
                <small>Email: info@carpinteria.com | Tel: 555-123-456</small>
            </div>
            <div class="text-end">
                <h4 class="mb-1">Factura #<?= $venta['id'] ?></h4>
                <div><strong>Fecha:</strong> <?= htmlspecialchars($venta['fecha']) ?></div>
            </div>
        </div>

        <!-- DATOS CLIENTE -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Cliente</h5>
                <p class="mb-0"><strong><?= htmlspecialchars($venta['cliente']) ?></strong></p>
                <p class="mb-0"><?= nl2br(htmlspecialchars($venta['direccion'])) ?></p>
                <p class="mb-0"><?= htmlspecialchars($venta['correo']) ?></p>
                <p><?= htmlspecialchars($venta['telefono']) ?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <h5>Condiciones</h5>
                <p class="mb-0"><strong>Tipo de pago:</strong> <?= ucfirst($venta['tipo_pago']) ?></p>
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
            <a href="ventas.php" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Imprimir
            </button>
        </div>

    </div>

 </div>

<?php include '../includes/footer.php'; ?>