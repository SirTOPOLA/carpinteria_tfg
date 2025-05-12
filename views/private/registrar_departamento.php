<?php
require_once '../includes/conexion.php';

$errores = [];
$nombre = '';
$descripcion = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar entrada
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    // Validación
    if (empty($nombre)) {
        $errores[] = "El nombre del departamento es obligatorio.";
    } elseif (strlen($nombre) > 100) {
        $errores[] = "El nombre no puede superar los 100 caracteres.";
    }

    // Validar que el nombre no exista
    if (empty($errores)) {
        $stmt = $pdo->prepare("SELECT id FROM departamentos WHERE nombre = :nombre");
        $stmt->execute([':nombre' => $nombre]);
        if ($stmt->fetch()) {
            $errores[] = "Ya existe un departamento con ese nombre.";
        }
    }

    // Insertar si no hay errores
    if (empty($errores)) {
        $stmt = $pdo->prepare("INSERT INTO departamentos (nombre, descripcion) VALUES (:nombre, :descripcion)");
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion ?: null
        ]);
        header("Location: departamentos.php?registro=exito");
        exit;
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="container-fluid py-4">
   
        <h4 class="mb-4">Registrar Nuevo Departamento</h4>

        <?php if ($errores): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Departamento <span class="text-danger">*</span></label>
                <input type="text" name="nombre" id="nombre" class="form-control" maxlength="100" required
                    value="<?= htmlspecialchars($nombre) ?>">
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción (opcional)</label>
                <textarea name="descripcion" id="descripcion" rows="4" class="form-control"><?= htmlspecialchars($descripcion) ?></textarea>
            </div>
            <div class="text-end">
                <a href="departamentos.php" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-success">Registrar</button>
            </div>
        </form>
    </div>
 

<?php include '../includes/footer.php'; ?>
