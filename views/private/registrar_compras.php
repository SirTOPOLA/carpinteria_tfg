<?php
require_once '../includes/conexion.php';

// Obtener proveedores y materiales
$stmtProv = $pdo->query("SELECT id, nombre FROM proveedores ORDER BY nombre ASC");
$proveedores = $stmtProv->fetchAll(PDO::FETCH_ASSOC);

$stmtMat = $pdo->query("SELECT id, nombre FROM materiales ORDER BY nombre ASC");
$materiales = $stmtMat->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>
<div class="container-fluid py-4">
    <h2>Registrar Nueva Compra</h2>
    <form id="formCompra" method="POST" action="guardar_compra.php">
        <div class="mb-3">
            <label for="proveedor_id" class="form-label">Proveedor:</label>
            <select name="proveedor_id" class="form-select" required>
                <option value="">Seleccione proveedor</option>
                <?php foreach ($proveedores as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha de compra:</label>
            <input type="date" name="fecha" class="form-control" required>
        </div>

        <hr>
        <h5>Materiales</h5>
        <div id="materialesContainer">
            <div class="row mb-2 materialRow">
                <div class="col-md-4">
                    <select name="material_id[]" class="form-select" required>
                        <option value="">Material</option>
                        <?php foreach ($materiales as $m): ?>
                            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="cantidad[]" min="1" class="form-control" placeholder="Cantidad" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="precio_unitario[]" step="0.01" class="form-control" placeholder="Precio" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="stock_minimo[]" min="1" class="form-control" placeholder="Stock mínimo" value="1" required>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary" id="agregarFila">+ Agregar material</button>
        <br><br>
        <button type="submit" class="btn btn-primary">Guardar Compra</button>
    </form>
</div>

<script>
    document.getElementById('agregarFila').addEventListener('click', () => {
        const container = document.getElementById('materialesContainer');
        const row = container.querySelector('.materialRow').cloneNode(true);
        row.querySelectorAll('input').forEach(input => input.value = '');
        container.appendChild(row);
    });
</script>
<?php include '../includes/footer.php'; ?>
