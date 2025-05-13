<?php
 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de compra no válido.");
}

$compra_id = (int) $_GET['id'];
if($compra_id <=0){
    header('location: index.php?vista=compras');
}
try {
    // Obtener datos de la compra
    $stmt = $pdo->prepare("SELECT * FROM compras WHERE id = ?");
    $stmt->execute([$compra_id]);
    $compra = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$compra) {
        header('location: index.php?vista=compras');
        exit; 
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
   // die("Error: " . htmlspecialchars($e->getMessage()));
}
?>

 
   <!-- Contenido -->
   <div id="content" class="container-fluid py-4">
    <h4>Editar Compra #<?= $compra_id ?></h4>
    <form id="formCompra" method="POST">
        <input type="hidden" name="compra_id" value="<?= $compra_id ?>">
        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label"><i class="bi bi-person"></i> Proveedor:</label>
                <select name="proveedor_id" class="form-select" required>
                    <option value="">Seleccione proveedor</option>
                    <?php foreach ($proveedores as $prov): ?>
                        <option value="<?= $prov['id'] ?>" <?= $prov['id'] == $compra['proveedor_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($prov['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha:</label>
                <input type="date" name="fecha" value="<?= $compra['fecha'] ?>" class="form-control" required>
            </div>
        </div>

        <h5>Materiales Comprados:</h5>
        <div class="row">
            <?php foreach ($detalles as $i => $item): ?>
                <div class="col-md-6">
                    <div class=" border rounded p-3 mb-3">

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
                                <input type="number" name="cantidades[]" value="<?= $item['cantidad'] ?>"
                                    class="form-control" required min="1">
                            </div>
                            <div class="col">
                                <label class="form-label">Precio Unitario:</label>
                                <input type="number" name="precios[]" value="<?= $item['precio_unitario'] ?>"
                                    class="form-control" step="0.01" required>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class=" d-flex justify-content-between px-5">
            <a href="index.php?vista=compras" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Cambios</button>
        </div>
    </form>
 </div>
 