<?php require "views/layouts/header.php"; ?>
<?php require "views/layouts/sidebar.php"; ?>

<div class="container-fluid py-4 px-lg-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Perfiles y Roles</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="?page=dashboard" class="text-decoration-none">Administración</a></li>
                    <li class="breadcrumb-item active">Roles</li>
                </ol>
            </nav>
        </div>

        <button type="button" class="btn btn-primary btn-lg rounded-pill shadow-sm px-4 d-flex align-items-center justify-content-center"
            data-bs-toggle="modal" data-bs-target="#modalCrearRol">
            <i class="bi bi-shield-lock-fill me-2"></i>
            <span>Nuevo Rol</span>
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Nombre del Rol</th>
                        <th>Descripción</th>
                        <th>Fecha Registro</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $r): ?>
                        <tr id="fila-rol-<?= $r['id_rol'] ?>">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($r["nombre"]) ?></span>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted"><?= htmlspecialchars($r["descripcion"] ?: 'Sin descripción') ?></small>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?= date('d/m/Y', strtotime($r["fecha_registro"])) ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button class="btn btn-white btn-sm border shadow-sm" 
                                            onclick="abrirEditarRol(<?= $r['id_rol'] ?>, '<?= addslashes($r['nombre']) ?>', '<?= addslashes($r['descripcion']) ?>')">
                                        <i class="bi bi-pencil text-primary"></i>
                                    </button>
                                    <button class="btn btn-white btn-sm px-3 border shadow-sm" 
                                            onclick="confirmarEliminarRol(<?= $r['id_rol'] ?>)">
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

<div class="modal fade" id="modalCrearRol" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Nuevo Rol de Sistema</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="?page=rolGuardar" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nombre del Rol</label>
                        <input type="text" name="nombre" class="form-control bg-light border-0" placeholder="Ej: Administrador" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Descripción</label>
                        <textarea name="descripcion" class="form-control bg-light border-0" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Guardar Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarRol" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Ajustar Nivel de Autoridad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="?page=rolActualizar" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_rol" id="edit_id_rol">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nombre del Rol</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control bg-light border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Descripción</label>
                        <textarea name="descripcion" id="edit_descripcion" class="form-control bg-light border-0" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Actualizar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Función para llenar y mostrar el modal de edición
    function abrirEditarRol(id, nombre, descripcion) {
        document.getElementById('edit_id_rol').value = id;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_descripcion').value = descripcion;
        
        const modal = new bootstrap.Modal(document.getElementById('modalEditarRol'));
        modal.show();
    }

    function confirmarEliminarRol(id) {
        Swal.fire({
            title: '¿Eliminar Rol?',
            text: "Se verificará que no existan usuarios vinculados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Sí, eliminar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `?page=rolEliminar&id=${id}`;
            }
        });
    }

    // Manejo de alertas desde la URL
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            Swal.fire({
                icon: 'success',
                title: '¡Logrado!',
                text: 'La operación se completó con éxito.',
                timer: 2000,
                showConfirmButton: false
            });
        }
        if (urlParams.has('error') && urlParams.get('error') === 'rol_en_uso') {
            Swal.fire({
                icon: 'error',
                title: 'Acción bloqueada',
                text: 'No puedes eliminar este rol porque tiene usuarios asignados.'
            });
        }
    });
</script>

<?php require "views/layouts/footer.php"; ?>