<?php
require_once("../includes/conexion.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID inválido.");
}

// Obtener datos actuales
$stmt = $pdo->prepare("SELECT * FROM categorias_materiales WHERE id = :id");
$stmt->execute([':id' => $id]);
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    die("Categoría no encontrada.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);

    if ($nombre === '') {
        $error = "El nombre es obligatorio.";
    } else {
        $stmt = $pdo->prepare("UPDATE categorias_materiales SET nombre = :nombre, descripcion = :descripcion WHERE id = :id");
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':id' => $id
        ]);
        header("Location: categoria_material.php?mensaje=Categoría actualizada");
        exit;
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
 
    <div class="container-fluid">
    <div class="row justify-content-center mt-5">
        <div class="col-md-7">
            <h4>Editar Categoría</h4>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control"
                        value="<?= htmlspecialchars($categoria['nombre']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <input type="text" name="descripcion" class="form-control"
                        value="<?= htmlspecialchars($categoria['descripcion']) ?>" required>
                </div>
                <div class="d-flex justify-content-between">

                    <a href="categoria_material.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
 
<?php include_once("../includes/footer.php"); ?>