<?php
require_once("../includes/conexion.php");

$errores = [];
$nombre = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre'] ?? '');

    if (empty($nombre)) {
        $errores[] = "El nombre de la categoría es obligatorio.";
    }

    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categorias_producto (nombre) VALUES (:nombre)");
            $stmt->execute([':nombre' => $nombre]);
            header("Location: categorias.php?mensaje=Categoria registrada correctamente");
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error al registrar la categoría: " . $e->getMessage();
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
   <div class="container-fluid py-4">
        <div class="col-md-7">
            <h4>Registrar Categoría</h4>

            <?php if ($errores): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de Categoría</label>
                    <input type="text" name="nombre" class="form-control" id="nombre"
                        value="<?= htmlspecialchars($nombre) ?>">
                </div>
                <div class="d-flex justify-content-between">
                    <a href="categorias.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
 
<?php include("../includes/footer.php"); ?>