<?php
// Si no hay sesión → redirige a login
if (!isset($_SESSION['usuario'])) {
    $_SESSION['alerta'] = "Debes registrarte para continuar con esta petición.";
    header("Location: login.php");
    exit;
}

$sql = "SELECT 
            u.*,
            r.nombre AS rol,
            e.nombre AS empleado_nombre,
            e.apellido AS empleado_apellido
            FROM usuarios u
            LEFT JOIN roles r ON u.rol_id = r.id
            LEFT JOIN empleados e ON u.empleado_id = e.id
            ";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<div id="content" class="container-fliud">
    <!-- Card con tabla de roles -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-person-lock me-2"></i> Gestión de Roles de Usuario
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar usuario..." id="buscador-roles">
            </div>
            <a href="index.php?vista=registrar_usuarios" class="btn btn-secondary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Usuario
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaRoles" class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-person-circle me-1"></i>Usuario</th>
                            <th><i class="bi bi-person-badge me-1"></i>Empleado</th>
                            <th><i class="bi bi-person-gear me-1"></i>Rol</th>
                            <th class="text-center"><i class="bi bi-check-circle me-1"></i>Activo</th>
                            <th class="text-center"><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($usuarios) > 0): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= $usuario['id'] ?></td>
                                    <td><?= htmlspecialchars($usuario['usuario']) ?></td>
                                    <td><?= htmlspecialchars($usuario['empleado_nombre'] . ' ' . $usuario['empleado_apellido']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($usuario['rol']) ?></td>
                                    <td class="text-center">
                                        <a href="#"
                                            class="btn btn-sm <?= $usuario['activo'] ? 'btn-success' : 'btn-danger' ?> activar-btn"
                                            data-id="<?= $usuario['id'] ?>" data-estado="<?= $usuario['activo'] ? '1' : '0' ?>">
                                            <i class="bi <?= $usuario['activo'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                                            <?= $usuario['activo'] ? 'Activado' : 'Desactivado' ?>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="index.php?vista=editar_usuarios&id=<?= $usuario['id'] ?>"
                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-muted text-center py-3">No se encontraron usuarios.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const botones = document.querySelectorAll(".activar-btn");

        botones.forEach(boton => {
            boton.addEventListener("click", function (e) {
                e.preventDefault();

                const id = this.dataset.id;
                const estadoActual = this.dataset.estado;

                if (!confirm(`¿Está seguro de ${estadoActual === '1' ? 'desactivar' : 'activar'} este usuario?`)) {
                    return;
                }

                fetch("api/activar_desactivar_usuario.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ id: id })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.ok) {
                            // Puedes recargar la tabla, cambiar íconos, texto, etc.
                           // alert("Estado actualizado correctamente");
                            location.reload(); // o actualiza solo la fila si prefieres
                        } else {
                            alert("Error al actualizar estado");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Error en la petición");
                    });
            });
        });
    });
</script>