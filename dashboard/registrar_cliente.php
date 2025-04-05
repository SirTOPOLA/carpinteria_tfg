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

    // ==========================
    // VALIDACIONES
    // ==========================
    if (empty($nombre)) {
        $errores[] = "El nombre del cliente es obligatorio.";
    } elseif (strlen($nombre) < 3 || strlen($nombre) > 100) {
        $errores[] = "El nombre debe tener entre 3 y 100 caracteres.";
    }

    if (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo no es válido.";
    }

    if (!empty($telefono) && !preg_match('/^[0-9\s\-\(\)\+]{7,20}$/', $telefono)) {
        $errores[] = "El número de teléfono no es válido.";
    }

    if (!empty($direccion) && strlen($direccion) > 255) {
        $errores[] = "La dirección no puede tener más de 255 caracteres.";
    }

    // ==========================
    // INSERCIÓN SI TODO ESTÁ CORRECTO
    // ==========================
    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO clientes (nombre, correo, telefono, direccion, fecha_registro)
                VALUES (:nombre, :correo, :telefono, :direccion, NOW())
            ");
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $telefono, PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $direccion, PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("Location: clientes.php?exito=1");
                exit;
            } else {
                $errores[] = "No se pudo registrar el cliente.";
            }
        } catch (PDOException $e) {
            $errores[] = "Error en base de datos: " . $e->getMessage();
        }
    }
}
?>

<?php include_once("../includes/header.php"); ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <h4 class="mb-4">Registrar Nuevo Cliente</h4>

            <!-- ERRORES -->
            <?php if (!empty($errores)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- FORMULARIO -->
            <form method="POST" novalidate>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="<?= htmlspecialchars($nombre ?? '') ?>" required maxlength="100">
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico (opcional)</label>
                    <input type="email" name="correo" id="correo" class="form-control"
                        value="<?= htmlspecialchars($correo ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono (opcional)</label>
                    <input type="text" name="telefono" id="telefono" class="form-control"
                        value="<?= htmlspecialchars($telefono ?? '') ?>" maxlength="20">
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección (opcional)</label>
                    <textarea name="direccion" id="direccion" class="form-control" rows="3"><?= htmlspecialchars($direccion ?? '') ?></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="clientes.php" class="btn btn-secondary">
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

<?php include_once("../includes/footer.php"); ?>
