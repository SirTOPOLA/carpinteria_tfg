<?php
require_once("../includes/conexion.php");

// Obtener categorías disponibles
$stmt = $pdo->query("SELECT id, nombre FROM categorias_material ORDER BY nombre ASC");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria_id = !empty($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : null;
    //$unidad = trim($_POST['unidad_medida'] ?? '');
    $stock = isset($_POST['stock']) ? (float) $_POST['stock'] : 0;
    $stock_minimo = isset($_POST['stock_minimo']) ? (float) $_POST['stock_minimo'] : 0;
    $precio_unitario = isset($_POST['precio_unitario']) ? (float) $_POST['precio_unitario'] : 0;

    // Validación mínima
    if ($nombre !== '' && $precio_unitario >= 0) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO materiales (nombre, descripcion, categoria_id, stock, stock_minimo, precio_unitario)
                VALUES (?, ?,  ?, ?, ?, ?)
            ");
            $stmt->execute([
                $nombre,
                $descripcion,
                $categoria_id,
               // $unidad,
                $stock,
                $stock_minimo,
                $precio_unitario
            ]);
            header('location: materiales.php');

        } catch (PDOException $e) {
            echo " " . $e->getMessage();
        }
    }
}
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
        <div class="row justify-content-center">
            <div class="col-md-7">
                <h4>Registrar Material</h4>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Material</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="categoria_id" class="form-label">Categoría</label>
                        <select name="categoria_id" class="form-select" required>
                            <option value="">Seleccione una categoría</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                                <div class="d-flex justify-content-between">

                                    <a href="materiales.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
                                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Registrar</button>
                                </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php include_once("../includes/footer.php"); ?>