<?php
require_once '../includes/conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de compra no válido.");
}

$compra_id = (int) $_GET['id'];

try {
    // Obtener datos de la compra
    $stmt = $pdo->prepare("SELECT * FROM compras WHERE id = ?");
    $stmt->execute([$compra_id]);
    $compra = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$compra) {
        die("Compra no encontrada.");
    }

    // Obtener proveedores
    $proveedores = $pdo->query("SELECT id, nombre FROM proveedores ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

    // Obtener materiales disponibles
    $materiales = $pdo->query("SELECT id, nombre FROM materiales ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

    // Obtener detalles de esta compra
    $stmt = $pdo->prepare("
        SELECT dc.id, dc.material_id, dc.cantidad, dc.precio_unitario
        FROM detalles_compra dc
        WHERE dc.compra_id = ?
    ");
    $stmt->execute([$compra_id]);
    $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . htmlspecialchars($e->getMessage()));
}
?>

<?php include '../includes/header.php'; ?>
<main class="container mt-4">
    <h2>Editar Compra #<?= $compra_id ?></h2>
    <form action="guardar_edicion_compra.php" method="POST">
        <input type="hidden" name="compra_id" value="<?= $compra_id ?>">

        <div class="mb-3">
            <label class="form-label">Proveedor:</label>
            <select name="proveedor_id" class="form-select" required>
                <option value="">Seleccione proveedor</option>
                <?php foreach ($proveedores as $prov): ?>
                    <option value="<?= $prov['id'] ?>" <?= $prov['id'] == $compra['proveedor_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($prov['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha:</label>
            <input type="date" name="fecha" value="<?= $compra['fecha'] ?>" class="form-control" required>
        </div>

        <h5>Materiales Comprados:</h5>
        <?php foreach ($detalles as $i => $item): ?>
            <div class="border rounded p-3 mb-3">
                <input type="hidden" name="detalle_ids[]" value="<?= $item['id'] ?>">

                <div class="mb-2">
                    <label class="form-label">Material:</label>
                    <select name="material_ids[]" class="form-select" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($materiales as $mat): ?>
                            <option value="<?= $mat['id'] ?>" <?= $mat['id'] == $item['material_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($mat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col">
                        <label class="form-label">Cantidad:</label>
                        <input type="number" name="cantidades[]" value="<?= $item['cantidad'] ?>" class="form-control" required min="1">
                    </div>
                    <div class="col">
                        <label class="form-label">Precio Unitario:</label>
                        <input type="number" name="precios[]" value="<?= $item['precio_unitario'] ?>" class="form-control" step="0.01" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Stock Mínimo:</label>
                        <input type="number" name="stocks_minimos[]" value="<?= $item['stock_minimo'] ?>" class="form-control" min="0" required>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</main>
<?php include '../includes/footer.php'; ?>
