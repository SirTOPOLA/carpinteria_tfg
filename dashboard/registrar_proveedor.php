<?php
require_once("../includes/conexion.php");

$errores = [];
$exito = "";

// PROCESAMIENTO DEL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    // VALIDACIONES
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    }

    if (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo no es válido.";
    }

    // INSERCIÓN
    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO proveedores (nombre, correo, telefono, direccion)
                VALUES (:nombre, :correo, :telefono, :direccion)
            ");
            $stmt->execute([
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':telefono' => $telefono,
                ':direccion' => $direccion
            ]);

            header("Location: proveedores.php?exito=1");
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error al guardar: " . $e->getMessage();
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
        <div class="col-md-7">
            <h4 class="mb-3">Registrar Proveedor</h4>

            <?php if (!empty($errores)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" required
                        value="<?= htmlspecialchars($nombre ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" name="correo" class="form-control"
                        value="<?= htmlspecialchars($correo ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                        value="<?= htmlspecialchars($telefono ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <textarea name="direccion" class="form-control"><?= htmlspecialchars($direccion ?? '') ?></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="proveedores.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</main>
<?php include_once("../includes/footer.php"); ?>