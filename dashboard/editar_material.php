<?php
require_once("../includes/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria_id = !empty($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : null;
    $unidad = trim($_POST['unidad_medida'] ?? '');
    $stock = (float) $_POST['stock'];
    $precio = (float) $_POST['precio_unitario'];

    if ($id > 0 && $nombre !== '' && $stock >= 0 && $precio >= 0) {
        $stmt = $pdo->prepare("UPDATE materiales SET nombre = ?, descripcion = ?, categoria_id = ?, unidad_medida = ?, stock = ?, precio_unitario = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $categoria_id, $unidad, $stock, $precio, $id]);
    }

    header("Location: materiales.php");
    exit;
}



?>



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

// Obtener categorías
$stmt_categorias = $pdo->query("SELECT id, nombre FROM categorias_material ORDER BY nombre ASC");
$categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
// dashboard.php principal
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
include '../includes/conexion.php'; // Asegúrate de tener la conexión a base de datos aquí
?>
<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid">
    <h4 class="mb-4">Editar Material</h4>

    <form method="POST">
        <input type="hidden" name="id" value="<?= $material['id'] ?>">

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
            <label>Categoría:</label>
            <select name="categoria_id" class="form-select">
                <option value="">-- Seleccionar --</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $material['categoria_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Unidad de Medida:</label>
            <input type="text" name="unidad_medida" class="form-control"
                value="<?= htmlspecialchars($material['unidad_medida']) ?>">
        </div>

        <div class="mb-3">
            <label>Stock:</label>
            <input type="number" step="0.01" name="stock" class="form-control" required
                value="<?= $material['stock'] ?>">
        </div>

        <div class="mb-3">
            <label>Precio Unitario (€):</label>
            <input type="number" step="0.01" name="precio_unitario" class="form-control" required
                value="<?= $material['precio_unitario'] ?>">
        </div>
        <div class="d-flex justify-content-between">

            <a href="materiales.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Cambios</button>
        </div>
    </form>
</div>
</main>
<?php include_once("../includes/footer.php"); ?>