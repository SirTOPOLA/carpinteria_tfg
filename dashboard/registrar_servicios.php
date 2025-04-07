<?php
require_once '../includes/conexion.php';
// Asegúrate de tener la conexión a base de datos aquí

// Procesamiento del formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = trim($_POST['precio'] ?? '');

    $errores = [];

    // Validaciones básicas
    if (empty($nombre)) {
        $errores[] = 'El nombre del servicio es obligatorio.';
    }

    if (!is_numeric($precio) || $precio < 0) {
        $errores[] = 'El precio debe ser un valor numérico positivo.';
    }

    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO servicios (nombre, descripcion, precio, fecha_creacion) VALUES (:nombre, :descripcion, :precio, NOW())");
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio
            ]);
            $exito = 'Servicio registrado correctamente.';
        } catch (PDOException $e) {
            $errores[] = 'Error al guardar el servicio: ' . $e->getMessage();
        }
    }
}

include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
?>
<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid d-flex justify-content-center">
        <div class="col-md-7">
            <h2 class="mb-4">Registrar Nuevo Servicio</h2>


            <?php if (!empty($errores)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif (!empty($exito)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($exito) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Servicio</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="precio" class="form-label">Precio (Bs)</label>
                    <input type="number" name="precio" id="precio" class="form-control" step="0.01" min="0" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="servicios.php" class="btn btn-secondary"><i class="bi bi-arrow-left"> </i> Volver</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Registrar Servicio</button>
                </div>
            </form>
        </div>
</main>

<?php
include_once('../includes/footer.php')
    ?>