<?php
require_once("../includes/conexion.php");

$errores = [];
$exito = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitización y validación
    $nombre = trim($_POST["nombre"] ?? '');
    $descripcion = trim($_POST["descripcion"] ?? '');

    if (empty($nombre)) {
        $errores[] = "El nombre del rol es obligatorio.";
    } elseif (strlen($nombre) > 50) {
        $errores[] = "El nombre del rol no debe exceder los 50 caracteres.";
    }

    if (strlen($descripcion) > 255) {
        $errores[] = "La descripción no debe exceder los 255 caracteres.";
    }

    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO roles (nombre, descripcion) VALUES (:nombre, :descripcion)");
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion ?: null
            ]);
            $exito = "Rol registrado correctamente.";
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $errores[] = "El nombre del rol ya existe.";
            } else {
                $errores[] = "Error al guardar el rol: " . $e->getMessage();
            }
        }
    }
}
?>

<?php include_once("../includes/header.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-light">
            <h4 class="mb-0">Registrar Nuevo Rol</h4>
        </div>
        <div class="card-body">

            <!-- Mostrar errores -->
            <?php if (!empty($errores)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Mensaje de éxito -->
            <?php if (!empty($exito)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($exito) ?>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre del Rol</label>
                        <input type="text" name="nombre" id="nombre" maxlength="50"
                            class="form-control" required placeholder="Ej: Administrador"
                            value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="descripcion" class="form-label">Descripción (opcional)</label>
                        <input type="text" name="descripcion" id="descripcion" maxlength="255"
                            class="form-control" placeholder="Descripción breve del rol"
                            value="<?= htmlspecialchars($_POST['descripcion'] ?? '') ?>">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Guardar Rol
                    </button>
                    <a href="roles.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once("../includes/footer.php"); ?>
