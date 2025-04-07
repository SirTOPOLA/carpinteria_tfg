<?php
require_once("../includes/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']); 

    if ($nombre === '') {
        $error = "El nombre es obligatorio.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO categorias_material (nombre  ) VALUES (:nombre  )");
        $stmt->execute([
            ':nombre' => $nombre 
        ]);
        header("Location: categoria_material.php?mensaje=Categoría registrada");
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
        <div class="col-md-7">
            <h4>Nueva Categoría de Material</h4>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                 <div class="d-flex justify-content-between">

                     <a href="categoria_material.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
                     <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Guardar</button>
                 </div>
            </form>
        </div>
    </div>
</div>
</main>
<?php include_once("../includes/footer.php"); ?>