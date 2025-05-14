<?php
require_once("../includes/conexion.php");

$errores = [];
$exito = "";

// PROCESAMIENTO DEL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // =============================
    // 1. VALIDACIÓN Y SANITIZACIÓN
    // =============================
    $nombre = trim($_POST['nombre'] ?? '');

    if (empty($nombre)) {
        $errores[] = "El nombre del rol es obligatorio.";
    } elseif (strlen($nombre) < 3 || strlen($nombre) > 50) {
        $errores[] = "El nombre del rol debe tener entre 3 y 50 caracteres.";
    }

    // =============================
    // 2. SI TODO ES VÁLIDO, INSERTAR
    // =============================
    if (empty($errores)) {
        try {
            // Verificar que no exista otro rol con el mismo nombre
            $verifica = $pdo->prepare("SELECT COUNT(*) FROM roles WHERE nombre = :nombre");
            $verifica->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $verifica->execute();
            $existe = $verifica->fetchColumn();

            if ($existe > 0) {
                $errores[] = "Ya existe un rol con ese nombre.";
            } else {
                // Insertar nuevo rol
                $stmt = $pdo->prepare("INSERT INTO roles (nombre) VALUES (:nombre)");
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $exito = "Rol registrado correctamente.";
                    header("Location: roles.php?exito=1");
                    exit;
                } else {
                    $errores[] = "Ocurrió un error al registrar el rol.";
                }
            }
        } catch (PDOException $e) {
            $errores[] = "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>

 


<!-- Contenido -->
<div class="container-fluid py-4">
    <div class="row g-4">
        <h4 class="mb-4">Registrar Nuevo Rol</h4>

        <!-- MENSAJES DE ERROR -->
        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- MENSAJE DE ÉXITO -->
        <?php if (!empty($exito)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($exito) ?></div>
        <?php endif; ?>

        <!-- FORMULARIO -->
        <form method="POST" novalidate>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Rol</label>
                <input type="text" name="nombre" id="nombre" class="form-control"
                    value="<?= htmlspecialchars($nombre ?? '') ?>" required maxlength="50">
            </div>

            <div class="d-flex justify-content-between">
                <a href="roles.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Registrar
                </button>
            </div>
        </form>
    </div>
</div>

 