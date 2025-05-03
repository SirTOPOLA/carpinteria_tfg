<?php
require_once("../includes/conexion.php");

$mensaje = '';
$usuario = [];
$empleado = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $usuario_id = (int)$_GET['id'];

    // Obtener usuario y su empleado asociado
    $stmt = $pdo->prepare("
        SELECT u.*, e.nombre AS emp_nombre, e.apellido AS emp_apellido
        FROM usuarios u
        LEFT JOIN empleados e ON u.empleado_id = e.id
        WHERE u.id = :id
    ");
    $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        header("Location: usuarios.php?error=Usuario no encontrado");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario_nombre = trim($_POST['usuario']);
        $rol = $_POST['rol'] ?? '';
        $nueva_password = trim($_POST['password']);

        // Validaciones
        if (empty($usuario_nombre) || empty($rol)) {
            $mensaje = "Por favor, complete todos los campos obligatorios.";
        } else {
            // Preparar SQL
            $sql_update = "UPDATE usuarios SET usuario = :usuario, rol = :rol";
            if (!empty($nueva_password)) {
                $sql_update .= ", password = :password";
            }
            $sql_update .= " WHERE id = :id";

            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindParam(':usuario', $usuario_nombre, PDO::PARAM_STR);
            $stmt_update->bindParam(':rol', $rol, PDO::PARAM_STR);
            $stmt_update->bindParam(':id', $usuario_id, PDO::PARAM_INT);
            if (!empty($nueva_password)) {
                $password_hash = password_hash($nueva_password, PASSWORD_DEFAULT);
                $stmt_update->bindParam(':password', $password_hash, PDO::PARAM_STR);
            }

            if ($stmt_update->execute()) {
                header("Location: usuarios.php?mensaje=Usuario actualizado correctamente");
                exit;
            } else {
                $mensaje = "Error al actualizar el usuario.";
            }
        }
    }
} else {
    header("Location: usuarios.php?error=ID de usuario no válido");
    exit;
}
?>
<?php
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
?>
<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="row d-flex justify-content-center">
        <div class="col-md-7">
            <h2 class="mb-4">Editar Usuario</h2>

            <?php if ($mensaje): ?>
                <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <form method="POST" class="p-4">
                <div class="mb-3">
                    <label class="form-label">Empleado</label>
                    <input type="text" class="form-control" disabled
                        value="<?= htmlspecialchars($usuario['emp_nombre'] . ' ' . $usuario['emp_apellido']) ?>">
                </div>

                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" name="usuario" id="usuario" class="form-control"
                        value="<?= htmlspecialchars($usuario['usuario']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
                    <select name="rol" id="rol" class="form-select" required>
                        <?php
                        $roles = ['administrador', 'vendedor', 'operario', 'diseñador'];
                        foreach ($roles as $r) {
                            $selected = ($usuario['rol'] === $r) ? 'selected' : '';
                            echo "<option value=\"$r\" $selected>" . ucfirst($r) . "</option>";
                        }
                        ?>
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
<?php include_once("../includes/footer.php"); ?>
