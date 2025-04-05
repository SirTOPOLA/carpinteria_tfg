<?php
require_once("../includes/conexion.php");

// Validar ID de venta
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: ventas.php?error=ID inválido");
    exit;
}

// Obtener información general de la venta
$sqlVenta = "
    SELECT v.id, v.fecha, c.nombre AS cliente, v.total
    FROM ventas v
    LEFT JOIN clientes c ON v.cliente_id = c.id
    WHERE v.id = :id
";
$stmt = $pdo->prepare($sqlVenta);
$stmt->execute([':id' => $id]);
$venta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$venta) {
    header("Location: ventas.php?error=Venta no encontrada");
    exit;
}

// Obtener los productos vendidos
$sqlDetalles = "
    SELECT dv.cantidad, dv.precio_unitario, p.nombre
    FROM detalle_venta dv
    INNER JOIN productos p ON dv.producto_id = p.id
    WHERE dv.venta_id = :id
";
$stmt = $pdo->prepare($sqlDetalles);
$stmt->execute([':id' => $id]);
$detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once("../includes/header.php"); ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Detalles de la Venta #<?= $venta['id'] ?></h4>
        <a href="ventas.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="mb-3">
        <strong>Cliente:</strong> <?= htmlspecialchars($venta['cliente']) ?><br>
        <strong>Fecha:</strong> <?= date("d/m/Y H:i", strtotime($venta['fecha'])) ?><br>
        <strong>Total:</strong> €<?= number_format($venta['total'], 2) ?>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                        <td><?= $item['cantidad'] ?></td>
                        <td>€<?= number_format($item['precio_unitario'], 2) ?></td>
                        <td>€<?= number_format($item['cantidad'] * $item['precio_unitario'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once("../includes/footer.php"); ?>
