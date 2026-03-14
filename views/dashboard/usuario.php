<?php require "views/layouts/header.php"; ?>
<?php require "views/layouts/sidebar.php"; ?>

<div class="container-fluid py-4 px-lg-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Gestión de Usuarios</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="?page=dashboard"
                            class="text-decoration-none">Administración</a></li>
                    <li class="breadcrumb-item active">Usuarios</li>
                </ol>
            </nav>
        </div>

        <button type="button"
            class="btn btn-primary btn-lg rounded-pill shadow-sm px-4 d-flex align-items-center justify-content-center"
            data-bs-toggle="modal" data-bs-target="#modalCrear">
            <i class="bi bi-person-plus-fill me-2"></i>
            <span>Nuevo Usuario</span>
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Usuario</th>
                        <th>Rol</th>
                        <th>Empleado</th>
                        <th class="text-center">Acceso</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr id="fila-<?= $u['id_usuario'] ?>" class="<?= !$u['activo'] ? 'opacity-50' : '' ?>"
                            style="transition: all 0.3s ease;">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($u['username']) ?>&background=random"
                                        class="rounded-circle me-2" width="35">
                                    <span class="fw-bold"><?= htmlspecialchars($u["username"]) ?></span>
                                </div>
                            </td>
                            <td><span
                                    class="badge bg-info-subtle text-info rounded-pill px-3"><?= htmlspecialchars($u["rol"]) ?></span>
                            </td>
                            <td><small
                                    class="text-muted"><?= $u["empleado"] ? htmlspecialchars($u["empleado"]) : 'Sin vincular' ?></small>
                            </td>

                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="form-check form-switch mb-1">
                                        <input class="form-check-input custom-switch pointer" type="checkbox" role="switch"
                                            <?= $u["activo"] ? 'checked' : '' ?>
                                            onchange="cambiarEstado(<?= $u['id_usuario'] ?>, this)">
                                    </div>
                                    <small class="fw-bold estado-texto <?= $u['activo'] ? 'text-success' : 'text-danger' ?>"
                                        style="font-size: 0.65rem;">
                                        <?= $u['activo'] ? 'ACTIVO' : 'SUSPENDIDO' ?>
                                    </small>
                                </div>
                            </td>

                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button class="btn btn-white btn-sm border shadow-sm"
                                        onclick="abrirEditar(<?= $u['id_usuario'] ?>, '<?= $u['username'] ?>', <?= $u['id_rol'] ?>, <?= $u['activo'] ?>)">
                                        <i class="bi bi-pencil text-primary"></i>
                                    </button>
                                    <button class="btn btn-white btn-sm px-3 border shadow-sm"
                                        onclick="confirmarEliminar(<?= $u['id_usuario'] ?>)">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Nuevo Acceso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="?page=usuarioGuardar" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Empleado</label>
                        <select name="id_empleado" class="form-select bg-light border-0">
                            <option value="">Seleccionar colaborador (Opcional)</option>
                            <?php foreach ($empleados as $e): ?>
                                <option value="<?= $e['id_empleado'] ?>"><?= $e['nombre'] ?>     <?= $e['apellido'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username</label>
                        <input type="text" name="username" class="form-control bg-light border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Contraseña</label>
                        <input type="password" name="password" class="form-control bg-light border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Rol</label>
                        <select name="id_rol" class="form-select bg-light border-0" required>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['id_rol'] ?>"><?= $r['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Crear Credenciales</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="?page=usuarioActualizar" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username</label>
                        <input type="text" name="username" id="edit_username" class="form-control bg-light border-0"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Rol</label>
                        <select name="id_rol" id="edit_rol" class="form-select bg-light border-0" required>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['id_rol'] ?>"><?= $r['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="activo" id="edit_activo" value="1">
                        <label class="form-check-label small fw-bold">Usuario Activo</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-dark w-100 rounded-pill py-2">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    // Configuración de Toast para avisos rápidos
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    // Detectar parámetros en la URL para operaciones normales
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('success')) {
            const accion = urlParams.get('success');
            let mensaje = "Operación realizada con éxito";

            if (accion === 'creado') mensaje = "¡Usuario registrado correctamente!";
            if (accion === 'actualizado') mensaje = "Datos actualizados en el sistema";
            if (accion === 'eliminado') mensaje = "Acceso eliminado definitivamente";

            Toast.fire({
                icon: 'success',
                title: mensaje
            });
        }

        if (urlParams.has('error')) {
            Swal.fire({
                icon: 'error',
                title: 'Error de validación',
                text: 'Por favor, verifica los datos ingresados (mínimo 6 caracteres en clave).',
                confirmButtonColor: '#0d6efd'
            });
        }
    });

    function abrirEditar(id, username, rol, activo) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_rol').value = rol;
        document.getElementById('edit_activo').checked = (activo == 1);

        var myModal = new bootstrap.Modal(document.getElementById('modalEditar'));
        myModal.show();
    }
    function confirmarEliminar(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción revocará el acceso permanentemente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `?page=usuarioEliminar&id=${id}`;
            }
        });
    }
    function cambiarEstado(id, element) {
        // Bloqueamos el switch momentáneamente para evitar doble click
        element.disabled = true;

        fetch(`?page=usuarioEstadoAjax&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });

                    // Actualizar el texto visual (ACTIVO/SUSPENDIDO)
                    const label = element.nextElementSibling;
                    if (element.checked) {
                        label.textContent = 'ACTIVO';
                        label.classList.replace('text-danger', 'text-success');
                    } else {
                        label.textContent = 'SUSPENDIDO';
                        label.classList.replace('text-success', 'text-danger');
                    }
                } else {
                    Swal.fire('Error', data.message, 'error');
                    element.checked = !element.checked; // Revertir si falló
                }
            })
            .catch(error => {
                console.error('Error:', error);
                element.checked = !element.checked;
            })
            .finally(() => {
                element.disabled = false;
            });
    }


</script>

<?php require "views/layouts/footer.php"; ?>