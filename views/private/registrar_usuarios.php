<?php
// Si no hay sesión → redirige a login
if (!isset($_SESSION['usuario'])) {
    $_SESSION['alerta'] = "Debes registrarte para continuar con esta petición.";
    header("Location: login.php");
    exit;
}
// Obtener lista de roles
$stmt = $pdo->query("SELECT id, nombre  FROM roles ORDER BY id");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de empleados para asociar al usuario
$stmt = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM empleados ORDER BY nombre");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div id="content" class="container-fluid py-4">
    <div class="container container-fliud-ms  py-4">
        <div class="card border-0 shadow rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4 py-3">
                <h5 class="mb-0">
                    <i class="bi bi-person-plus-fill me-2"></i>Registrar Usuario
                </h5>
            </div>

            <div class="card-body">
                <form id="formUsuario" method="POST" class="row g-3 needs-validation" novalidate>

                    <!-- Nombre de Usuario -->
                    <div class="col-md-6">
                        <label for="usuario" class="form-label">Nombre de Usuario <span
                                class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                            <input type="email" name="usuario" id="usuario" class="form-control"
                                placeholder="Marvel88@example.net" required>
                            <div class="invalid-feedback">El correo de usuario es obligatorio.</div>
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="password" id="password" class="form-control" required>
                            <div class="invalid-feedback">La contraseña es obligatoria.</div>
                        </div>
                    </div>

                    <!-- Rol -->
                    <div class="col-md-6">
                        <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-person-badge-fill"></i></span>
                            <select name="rol" id="rol" class="form-select" required>
                                <option value="">Seleccione un rol</option>
                                <?php foreach ($roles as $rol): ?>
                                    <option value="<?= htmlspecialchars($rol['id']) ?>">
                                        <?= htmlspecialchars($rol['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Seleccione un rol.</div>
                        </div>
                    </div>

                    <!-- Empleado Asociado -->
                    <div class="col-md-6">
                        <label for="empleado_id" class="form-label">Empleado Asociado</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-people-fill"></i></span>
                            <select name="empleado_id" id="empleado_id" class="form-select">
                                <option value="">Sin asociar</option>
                                <?php foreach ($empleados as $emp): ?>
                                    <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['nombre_completo']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="col-12 d-flex justify-content-between mt-3">
                    <a href="index.php?vista=usuarios" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="bi bi-arrow-left-circle me-1"></i>Cancelar
                            </a>

                            <button type="submit" class="btn btn-outline-success rounded-pill px-4">
                                <i class="bi bi-save-fill me-1"></i>Registrar
                            </button>

                        </div>
                  

                </form>
            </div>
        </div>
    </div>

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