<?php
// Validar ID recibido
$_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($_id <= 0) {
    $_SESSION['alerta'] = "ID proporcionado no válido";
    header("Location: index.php?vista=usuarios");
    exit;
}

// Validar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    $_SESSION['alerta'] = "Debes iniciar sesión para continuar.";
    header("Location: login.php");
    exit;
}

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
$stmt->bindParam(':id', $_id, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
// Validar si existe
if (!$usuario) {
    $_SESSION['alerta'] = "No existe un registro para el ID proporcionado.";
    header("Location: index.php?vista=usuarios");
    exit;
}
// Obtener roles
$stmt = $pdo->prepare("SELECT * FROM roles");
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Obtener empleados
$stmt = $pdo->prepare("SELECT * FROM empleados");
$stmt->execute();
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div id="content" class="container-fluid py-4">
    <div class="container container-fluid-sm py-4">
        <div class="card border-0 shadow rounded-4">
            <div class="card-header bg-warning text-dark rounded-top-4 py-3">
                <h5 class="mb-0 text-white">
                    <i class="bi bi-pencil-square me-2"></i>Editar Usuario
                </h5>
            </div>

            <div class="card-body">
                <form id="formUsuarioEditar" method="POST" class="row g-3 needs-validation" novalidate>
                    <input type="hidden" id="usuario_id" value="<?= htmlspecialchars($usuario['id']) ?> ">
                    <!-- Nombre de Usuario -->
                    <div class="col-md-6">
                        <label for="usuario" class="form-label">Nombre de Usuario <span
                                class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                            <input type="email" name="usuario" id="usuario" class="form-control" required
                                value="<?= htmlspecialchars($usuario['usuario']) ?>">
                            <div class="invalid-feedback">El correo de usuario es obligatorio.</div>
                        </div>
                    </div>

                    <!-- Contraseña (opcional en edición) -->
                    <div class="col-md-6">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Dejar en blanco para no cambiar">
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
                                    <option value="<?= $rol['id'] ?>" <?= $rol['id'] == $usuario['rol_id'] ? 'selected' : '' ?>>
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
                                <option value="">Sin asociar </option>
                                <?php foreach ($empleados as $emp): ?>
                                    <option value="<?= $emp['id'] ?>" <?= $emp['id'] == $usuario['empleado_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($emp['nombre']) ?>     <?= htmlspecialchars($emp['apellido']) ?>
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
                        <button type="submit" class="btn btn-warning rounded-pill px-4">
                            <i class="bi bi-save2-fill me-1"></i>Actualizar Usuario
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>


<script>
    document.getElementById('formUsuarioEditar').addEventListener('submit', function (e) {
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
        fetch('api/actualizar_usuario.php', {
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