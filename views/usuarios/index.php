<?php require "views/layouts/header.php"; ?>
<?php require "views/layouts/sidebar.php"; ?>

<div class="container shadow-sm">
    <div class="container-fluid py-4 px-lg-5">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1">Gestión de Usuarios</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard" class="text-decoration-none">Administración</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>
                </nav>
            </div>
            
            <a href="?page=usuarioCrear" class="btn btn-primary btn-lg rounded-pill shadow-sm px-4 d-flex align-items-center justify-content-center">
                <i class="bi bi-person-plus-fill me-2"></i>
                <span>Nuevo Usuario</span>
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="text-muted small fw-bold uppercase">Lista de Acceso al Sistema</span>
                    </div>
                    <div class="col-auto">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control bg-light border-0" placeholder="Filtrar por nombre...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4" style="width: 80px;">ID</th>
                            <th>Usuario / Credencial</th>
                            <th>Rol del Sistema</th>
                            <th>Empleado Vinculado</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones Operativas</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td class="ps-4 text-muted">#<?= $u["id_usuario"] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <img src="https://ui-avatars.com/api/?name=<?= $u["username"] ?>&background=random&color=fff" class="rounded-circle" width="35">
                                    </div>
                                    <span class="fw-bold text-slate-700"><?= $u["username"] ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill border border-info-subtle">
                                    <i class="bi bi-shield-lock me-1"></i> <?= $u["rol"] ?>
                                </span>
                            </td>
                            <td>
                                <span class="text-dark small"><i class="bi bi-person me-1"></i> <?= $u["empleado"] ?></span>
                            </td>
                            <td>
                                <?php if ($u["activo"]): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i> Activo
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                                        <i class="bi bi-x-circle-fill me-1"></i> Inactivo
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm rounded-3">
                                    <a href="?page=usuarioEditar&id=<?= $u["id_usuario"] ?>" class="btn btn-white btn-sm px-3 border" title="Editar Perfil">
                                        <i class="bi bi-pencil text-primary"></i>
                                    </a>
                                    <a href="?page=usuarioEliminar&id=<?= $u["id_usuario"] ?>" class="btn btn-white btn-sm px-3 border" title="Eliminar Usuario" onclick="return confirm('¿Estás seguro de desactivar este acceso?')">
                                        <i class="bi bi-trash text-danger"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-0 py-3 text-center">
                <small class="text-muted">Mostrando <?= count($usuarios) ?> usuarios registrados en la base de datos de seguridad.</small>
            </div>
        </div>
    </div>
</div>

<?php require "views/layouts/footer.php"; ?>