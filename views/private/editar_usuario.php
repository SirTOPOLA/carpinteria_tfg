<?php
require_once("../includes/conexion.php");

$mensaje = '';
$usuario = [];
$empleado = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $usuario_id = (int)$_GET['id'];

    // Obtener usuario y su empleado asociado
    $stmt = $pdo->prepare("SELECT 
                        u.*,
                        e.nombre AS emp_nombre, 
                        e.apellido AS emp_apellido,
                        r.nombre AS rol
                        FROM usuarios u
                        LEFT JOIN empleados e ON u.empleado_id = e.id
                        LEFT JOIN roles r ON u.rol_id = r.id
                        WHERE u.id = :id
                    ");
    $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    // Obtener roles
    $stmt = $pdo->prepare("SELECT * FROM roles"); 
    $stmt->execute();
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$usuario) {
        header("Location: usuarios.php?error=Usuario no encontrado");
        exit;
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
   <div class="container-fluid py-4">
    <div class="row d-flex justify-content-center">
        <div class="col-md-7">
            <h2 class="mb-4">Editar Usuario</h2>

            <?php if ($mensaje): ?>
                <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <form action="../php/actualizar_usuarios.php" method="POST" class="p-4">
                <input type="hidden" class="form-control" name="usuario_id"
                    value="<?= htmlspecialchars($usuario['id']) ?>">

                <div class="mb-3">
                    <label class="form-label">Empleado</label>
                    <input type="text" class="form-control" disabled
                        value="<?= htmlspecialchars($usuario['emp_nombre'] . ' ' . $usuario['emp_apellido']) ?>">
                </div>

                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="email" name="usuario" id="usuario" class="form-control"
                        value="<?= htmlspecialchars($usuario['usuario']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
                    <select name="rol" id="rol" class="form-select" required>
                        <option value="<?= htmlspecialchars($usuario['rol_id']) ?> "><?= htmlspecialchars($usuario['rol']) ?> </option>
                        <?php  foreach ($roles as $r):  ?>
                            
                           <option value="<?= htmlspecialchars($r['id']) ?> "><?= htmlspecialchars($r['nombre']) ?> </option>
                        <?php endforeach;    ?>
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
</div>
<?php include_once("../includes/footer.php"); ?>
