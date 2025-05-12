<?php
  
// Obtener lista de roles
$stmt = $pdo->query("SELECT id, nombre  FROM roles ORDER BY id");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de empleados para asociar al usuario
$stmt = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM empleados ORDER BY nombre");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div id="content" class="container-fluid py-4">
        <h4 class="mb-4">Registrar Usuario</h4>

        <form  id="formUsuario"  method="POST" class="row g-3 needs-validation" novalidate>

            <div class="col-12 col-md-6">
                <label for="usuario" class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                <input type="email" name="usuario" placeholder="Marvel88@example.net" id="usuario" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
                <select name="rol" id="rol" class="form-select" required>
                    <option value="">Seleccione un rol</option>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?= htmlspecialchars($rol['id']) ?>"> <?= htmlspecialchars($rol['nombre']) ?></option>
                        
                        <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-6">
                <label for="empleado_id" class="form-label">Empleado Asociado</label>
                <select name="empleado_id" id="empleado_id" class="form-select">
                    <option value="">Sin asociar</option>
                    <?php foreach ($empleados as $emp): ?>
                        <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['nombre_completo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Usuario
                </button>
                <a href="index.php?vista=usuarios" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formUsuario');

    form.addEventListener('submit', async function (e) {
        e.preventDefault(); // Evita el envío tradicional

        const datos = {
            usuario: document.getElementById('usuario').value.trim(),
            password: document.getElementById('password').value,
            rol: document.getElementById('rol').value,
            empleado_id: document.getElementById('empleado_id').value
        };

        try {
            const respuesta = await fetch('api/guardar_usuario.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            });

            const resultado = await respuesta.json();

            // Mostrar mensaje
            alert(resultado.mensaje);

            if (resultado.ok) {
                window.location.href = 'index.php?vista=usuarios';
            }

        } catch (error) {
            console.error('Error al enviar el formulario:', error);
            alert('Hubo un error al registrar el usuario.');
        }
    });
});
</script>
