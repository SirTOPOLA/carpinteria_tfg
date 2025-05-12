<?php
require_once("../includes/conexion.php");

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: roles.php?error=ID inválido");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM roles WHERE id = ?");
$stmt->execute([$id]);
$rol = $stmt->fetch();

if (!$rol) {
    header("Location: roles.php?error=Rol no encontrado");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    if ($nombre === '') {
        $error = "El nombre es obligatorio.";
    } else {
        $update = $pdo->prepare("UPDATE roles SET nombre = ? WHERE id = ?");
        $update->execute([$nombre, $id]);
        header("Location: roles.php?mensaje=Rol actualizado");
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
   <div class="container-fluid py-4">
    <h4>Editar Rol</h4>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="nombre" class="form-label">Nombre del Rol</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($rol['nombre']) ?>" class="form-control" required>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="roles.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
 
<?php include("../includes/footer.php"); ?>
