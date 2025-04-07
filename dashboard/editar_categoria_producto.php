<?php
require_once("../includes/conexion.php");
// Validar que se pasó un ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID inválido.');
}

$id = intval($_GET['id']);

// Obtener datos actuales de la categoría
$stmt = $pdo->prepare("SELECT * FROM categorias_producto WHERE id = ?");
$stmt->execute([$id]);
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    die('Categoría no encontrada.');
}

// Procesar formulario al enviar
$errores = [];
$nombre = $categoria['nombre'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);

    // Validaciones
    if (empty($nombre)) {
        $errores[] = "El nombre de la categoría es obligatorio.";
    } elseif (strlen($nombre) > 100) {
        $errores[] = "El nombre no debe superar los 100 caracteres.";
    }

    if (empty($errores)) {
        // Actualizar en la base de datos
        $stmt = $pdo->prepare("UPDATE categorias_producto SET nombre = ? WHERE id = ?");
        $stmt->execute([$nombre, $id]);

        // Redireccionar al listado
        header('Location: categoria_producto.php?edit=success');
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
<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid">
        <div class="row justify-content-center mt-5">
            <div class="col-md-7">

                <h2 class="mb-4">Editar Categoría</h2>

                <?php if ($errores): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errores as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la categoría</label>
                        <input type="text" name="nombre" id="nombre" class="form-control"
                            value="<?= htmlspecialchars($nombre) ?>" required maxlength="100">
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Guardar
                        cambios</button>
                    <a href="categoria_producto.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>

    </div>
</main>
<?php include_once("../includes/footer.php"); ?>