<?php
require_once("../includes/conexion.php");

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: materiales.php");
    exit;
}

// Obtener material
$stmt = $pdo->prepare("SELECT * FROM materiales WHERE id = ?");
$stmt->execute([$id]);
$material = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$material) {
    header("Location: materiales.php");
    exit;
}
?>

<?php
// dashboard.php principal
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
include '../includes/conexion.php'; // Asegúrate de tener la conexión a base de datos aquí
?>
<!-- Contenido -->
<div class="container-fluid py-4">
    <div class="container-fluid">
        <h4 class="mb-4">Editar Material</h4>

        <form action="../php/actualizar_materiales.php" method="POST">
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
                <a href="materiales.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
    <?php include_once("../includes/footer.php"); ?>