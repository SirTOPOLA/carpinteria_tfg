<?php
 

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php?vista=materiales");
    exit;
}

// Obtener material
$stmt = $pdo->prepare("SELECT * FROM materiales WHERE id = ?");
$stmt->execute([$id]);
$material = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$material) {
    header("Location: index.php?vista=materiales");
    exit;
}
?>
 
<!-- Contenido -->
<div id="content" class="container-fluid py-4">
    <div class="container-fluid">
        <h4 class="mb-4">Editar Material</h4>

        <form id="formEditarMaterial" method="POST">
            <input type="hidden" name="material_id" value="<?= $material['id'] ?>">

            <div class="mb-3">
                <label>Nombre:</label>
                <input type="text" name="nombre" class="form-control" required
                    value="<?= htmlspecialchars($material['nombre']) ?>">
            </div>
            <div class="mb-3">
                <label>Descripción:</label>
                <textarea name="descripcion" class="form-control"
                    rows="3"><?= htmlspecialchars($material['descripcion']) ?></textarea>
            </div>
            <div class="mb-3">
                <label>Unidad de Medida:</label>
                <input type="text" name="unidad_medida" class="form-control"
                    value="<?= htmlspecialchars($material['unidad_medida']) ?>">
            </div>

            <div class="mb-3">
                <label>Stock Mímino:</label>
                <input type="number" name="stock_minimo" class="form-control" required
                    value="<?= htmlspecialchars($material['stock_minimo']) ?>">
            </div>


            <div class="d-flex justify-content-between">
                <a href="index.php?vista=materiales" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
 