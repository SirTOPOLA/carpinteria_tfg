<?php
require_once("../includes/conexion.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: servicios.php?error=ID inválido");
    exit;
}

// Obtener datos del servicio
$stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = :id");
$stmt->execute([':id' => $id]);
$servicio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$servicio) {
    header("Location: servicios.php?error=Servicio no encontrado");
    exit;
}

// Procesar envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = trim($_POST['precio'] ?? '');

    // Validaciones simples
    if (empty($nombre) || !is_numeric($precio) || $precio < 0) {
        $error = "El nombre es obligatorio y el precio debe ser un valor numérico positivo.";
    } else {
        $stmt = $pdo->prepare("UPDATE servicios SET nombre = :nombre, descripcion = :descripcion, precio = :precio WHERE id = :id");
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':id' => $id
        ]);
        header("Location: servicios.php?mensaje=Servicio actualizado");
        exit;
    }
}
?>

<?php
// dashboard.php principal
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
?>
   <div class="container-fluid py-4">
    <h4>Editar Servicio</h4>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="nombre" class="form-label">Nombre del Servicio</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($servicio['nombre']) ?>" required>
        </div>

        <div class="col-md-6">
            <label for="precio" class="form-label">Precio (Bs)</label>
            <input type="number" name="precio" id="precio" class="form-control" step="0.01" min="0" value="<?= htmlspecialchars($servicio['precio']) ?>" required>
        </div>

        <div class="col-12">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3"><?= htmlspecialchars($servicio['descripcion']) ?></textarea>
        </div>

        <div class="col-12 text-end">
            <a href="servicios.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </form>
</div>
 
<?php include_once("../includes/footer.php"); ?>
