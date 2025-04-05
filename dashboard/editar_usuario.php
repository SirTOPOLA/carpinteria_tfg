<?php
require_once("../includes/conexion.php");

$id = $_GET['id'] ?? '';
if (!is_numeric($id)) {
    header("Location: usuarios_lista.php");
    exit;
}

$errores = [];
$usuario = null;

// Obtener roles
$roles_stmt = $pdo->query("SELECT id, nombre FROM roles ORDER BY nombre ASC");
$roles = $roles_stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener datos actuales del usuario
$stmt_usuario = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt_usuario->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_usuario->execute();
$usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: usuarios_lista.php");
    exit;
}

// PROCESAMIENTO DEL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $rol_id = $_POST['rol_id'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $contrasena = trim($_POST['contrasena'] ?? '');

    // VALIDACIONES
    if (empty($nombre) || strlen($nombre) < 3 || strlen($nombre) > 100) {
        $errores[] = "El nombre debe tener entre 3 y 100 caracteres.";
    }

    if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Correo electrónico inválido.";
    }

    if (empty($rol_id) || !is_numeric($rol_id)) {
        $errores[] = "Rol no válido.";
    }

    if ($estado !== 'activo' && $estado !== 'inactivo') {
        $errores[] = "Estado inválido.";
    }

    // Verificar si el correo ya existe en otro usuario
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo AND id != :id");
    $stmt_check->execute([
        ':correo' => $correo,
        ':id' => $id
    ]);

    if ($stmt_check->fetchColumn() > 0) {
        $errores[] = "El correo ya está en uso por otro usuario.";
    }

    // ACTUALIZACIÓN
    if (empty($errores)) {
        try {
            // Si se proporcionó nueva contraseña
            if (!empty($contrasena)) {
                $hash = password_hash($contrasena, PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios 
                        SET nombre = :nombre, correo = :correo, rol_id = :rol_id, estado = :estado, contrasena = :contrasena 
                        WHERE id = :id";
            } else {
                $sql = "UPDATE usuarios 
                        SET nombre = :nombre, correo = :correo, rol_id = :rol_id, estado = :estado 
                        WHERE id = :id";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!empty($contrasena)) {
                $stmt->bindParam(':contrasena', $hash);
            }

            if ($stmt->execute()) {
                header("Location: usuarios.php?actualizado=1");
                exit;
            } else {
                $errores[] = "Error al actualizar el usuario.";
            }
        } catch (PDOException $e) {
            $errores[] = "Error de base de datos: " . $e->getMessage();
        }
    }
}
?>

<?php include_once("../includes/header.php"); ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <h4 class="mb-4">Editar Usuario</h4>

            <?php if (!empty($errores)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" required maxlength="100">
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" id="correo" class="form-control"
                        value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="rol_id" class="form-label">Rol asignado</label>
                    <select name="rol_id" id="rol_id" class="form-select" required>
                        <option value="">-- Seleccionar --</option>
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?= $rol['id'] ?>" <?= $usuario['rol_id'] == $rol['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($rol['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select" required>
                        <option value="activo" <?= $usuario['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="inactivo" <?= $usuario['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="contrasena" class="form-label">Nueva contraseña (opcional)</label>
                    <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="Dejar en blanco para mantener la actual">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="usuarios.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once("../includes/footer.php"); ?>
