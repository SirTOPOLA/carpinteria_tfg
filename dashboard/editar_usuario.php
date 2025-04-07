<?php
include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/nav.php';
include '../includes/conexion.php';

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de usuario inválido.</div>";
    exit;
}

$id = (int) $_GET['id'];

// Obtener usuario actual
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "<div class='alert alert-danger'>Usuario no encontrado.</div>";
    exit;
}

// Procesar formulario
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $rol_id = (int) $_POST['rol_id'];
    $password_nueva = trim($_POST['password']);

    if ($nombre === '' || $correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "<div class='alert alert-danger'>Por favor, completa todos los campos correctamente.</div>";
    } else {
        // Actualizar con o sin contraseña
        if ($password_nueva !== '') {
            $password_hash = password_hash($password_nueva, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nombre = ?, correo = ?, rol_id = ?, password = ? WHERE id = ?";
            $params = [$nombre, $correo, $rol_id, $password_hash, $id];
        } else {
            $sql = "UPDATE usuarios SET nombre = ?, correo = ?, rol_id = ? WHERE id = ?";
            $params = [$nombre, $correo, $rol_id, $id];
        }

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute($params)) {
            $mensaje = "<div class='alert alert-success'>Usuario actualizado correctamente.</div>";
            // Refrescar datos
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $mensaje = "<div class='alert alert-danger'>Ocurrió un error al actualizar.</div>";
        }
    }
}

// Obtener roles
$roles = $pdo->query("SELECT id, nombre FROM roles")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="row d-flex justify-content-center">
        <div class="col-md-7 ">
            <h2 class="mb-4">Editar Usuario</h2>

            <?= $mensaje ?>

            <form method="POST" class="  p-4">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" name="correo" id="correo" class="form-control"
                        value="<?= htmlspecialchars($usuario['correo']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="rol_id" class="form-label">Rol</label>
                    <select name="rol_id" id="rol_id" class="form-select" required>
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?= $rol['id'] ?>" <?= $rol['id'] == $usuario['rol_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($rol['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Nueva Contraseña (opcional)</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <div class="form-text">Solo completa este campo si deseas cambiar la contraseña.</div>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="usuarios.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>

                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>