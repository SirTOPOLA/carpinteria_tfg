<?php 

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
        header("Location: index.php?vista=usuarios&error=Usuario no encontrado");
        exit;
    }

     
} else {
    header("Location: usuarios.php?error=ID de usuario no válido");
    exit;
}
?>
 
   <div id="content" class="container-fluid py-4">
    <div class="row d-flex justify-content-center">
        <div class="col-md-7">
            <h2 class="mb-4">Editar Usuario</h2>

            <?php if ($mensaje): ?>
                <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <form id="formEditarUsuario" method="POST" class="p-4">
                <input id="usuario_id" type="hidden" class="form-control" name="usuario_id"
                    value="<?= htmlspecialchars($usuario['id']) ?>">

                <div class="mb-3">
                    <label class="form-label">Empleado</label>
                    <input type="text" class="form-control" id="empleado_id" disabled
                        value="<?= htmlspecialchars($usuario['emp_nombre'] . ' ' . $usuario['emp_apellido']) ?>">
                </div>

                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input   type="email" name="usuario" id="usuario" class="form-control"
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
                    <input  type="password" name="password" id="password" class="form-control">
                    <div class="form-text">Solo completa este campo si deseas cambiar la contraseña.</div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?vista=usuarios" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div> 


<script>
document.getElementById('formEditarUsuario').addEventListener('submit', function(e) {
    e.preventDefault(); // evitar envío normal

    // Obtener datos del formulario
    const formData = {
        id: document.getElementById('usuario_id').value,
        usuario: document.getElementById('usuario').value,
        password: document.getElementById('password').value,
        rol: document.getElementById('rol').value,
        empleado_id: document.getElementById('empleado_id').value
    };

    // Enviar con fetch
    fetch('api/editar_usuario.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) {
            alert('Usuario editado con éxito');
            window.location.href = 'index.php?vista=usuarios';
        } else {
            alert('Error: ' + data.mensaje);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error en la solicitud');
    });
});
</script>
