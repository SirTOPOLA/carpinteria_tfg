<?php
require_once("../includes/conexion.php");

$errores = [];
$exito = "";

// Obtener roles para el select
$roles_stmt = $pdo->query("SELECT id, nombre FROM roles ORDER BY nombre ASC");
$roles = $roles_stmt->fetchAll(PDO::FETCH_ASSOC);

// PROCESAMIENTO DEL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $rol_id = $_POST['rol_id'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    // ==========================
    // VALIDACIONES
    // ==========================
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    } elseif (strlen($nombre) < 3 || strlen($nombre) > 100) {
        $errores[] = "El nombre debe tener entre 3 y 100 caracteres.";
    }

    if (empty($correo)) {
        $errores[] = "El correo es obligatorio.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo no es válido.";
    }

    if (empty($rol_id) || !is_numeric($rol_id)) {
        $errores[] = "Debe seleccionar un rol válido.";
    }

    if ($estado !== 'activo' && $estado !== 'inactivo') {
        $errores[] = "Estado inválido.";
    }

    if (empty($contrasena)) {
        $errores[] = "La contraseña es obligatoria.";
    } elseif (strlen($contrasena) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres.";
    }

    // ==========================
    // VERIFICACIÓN DE CORREO ÚNICO
    // ==========================
    if (empty($errores)) {
        try {
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo");
            $stmt_check->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt_check->execute();

            if ($stmt_check->fetchColumn() > 0) {
                $errores[] = "Ya existe un usuario con ese correo.";
            }
        } catch (PDOException $e) {
            $errores[] = "Error al verificar el correo: " . $e->getMessage();
        }
    }

    // ==========================
    // INSERCIÓN SI TODO ESTÁ CORRECTO
    // ==========================
    if (empty($errores)) {
        try {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO usuarios (nombre, correo, contrasena, rol_id, estado, fecha_creacion)
                VALUES (:nombre, :correo, :contrasena, :rol_id, :estado, NOW())
            ");
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
            $stmt->bindParam(":contrasena", $hash, PDO::PARAM_STR);
            $stmt->bindParam(":rol_id", $rol_id, PDO::PARAM_INT);
            $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("Location: usuarios.php?exito=1");
                exit;
            } else {
                $errores[] = "No se pudo registrar el usuario.";
            }
        } catch (PDOException $e) {
            $errores[] = "Error al guardar en base de datos: " . $e->getMessage();
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
    <div class="row justify-content-center">
        <div class="col-md-7">
            <h4 class="mb-4">Registrar Nuevo Usuario</h4>

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
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" id="correo" class="form-control"
                        value="<?= htmlspecialchars($correo ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" name="contrasena" id="contrasena" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="rol_id" class="form-label">Rol asignado</label>
                    <select name="rol_id" id="rol_id" class="form-select" required>
                        <option value="">-- Seleccionar --</option>
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?= $rol['id'] ?>" <?= isset($rol_id) && $rol_id == $rol['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($rol['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select" required>
                        <option value="">-- Seleccionar --</option>
                        <option value="activo" <?= ($estado ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="inactivo" <?= ($estado ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="usuarios.php" class="btn btn-secondary">
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
</main>
<?php include_once("../includes/footer.php"); ?>
