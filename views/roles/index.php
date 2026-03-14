<?php require "views/layouts/header.php"; ?>
<?php require "views/layouts/sidebar.php"; ?>


    <div class="container-fluid py-4 px-lg-5">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1">Roles y Permisos</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard" class="text-decoration-none">Administración</a></li>
                        <li class="breadcrumb-item active">Configuración de Roles</li>
                    </ol>
                </nav>
            </div>
            
            <a href="?page=rolCrear" class="btn btn-primary btn-lg rounded-pill shadow-sm px-4 d-flex align-items-center justify-content-center">
                <i class="bi bi-shield-plus me-2"></i>
                <span>Definir Nuevo Rol</span>
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-4 border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="text-muted small fw-bold text-uppercase tracking-wider">Jerarquía de Seguridad</span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4" style="width: 100px;">ID</th>
                            <th>Nombre del Rol</th>
                            <th>Descripción de Responsabilidades</th>
                            <th>Fecha de Registro</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php foreach ($roles as $r): ?>
                        <tr>
                            <td class="ps-4 text-muted">#<?= $r["id_rol"] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-subtle p-2 rounded-3 me-3 text-primary">
                                        <i class="bi bi-person-badge-fill fs-5"></i>
                                    </div>
                                    <span class="fw-bold text-slate-700"><?= $r["nombre"] ?></span>
                                </div>
                            </td>
                            <td>
                                <p class="mb-0 text-muted small py-1" style="max-width: 350px;">
                                    <?= $r["descripcion"] ?: '<span class="fst-italic">Sin descripción definida</span>' ?>
                                </p>
                            </td>
                            <td>
                                <div class="text-dark small">
                                    <i class="bi bi-calendar3 me-1 text-muted"></i>
                                    <?= date("d/m/Y", strtotime($r["fecha_registro"])) ?>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm rounded-3">
                                    <a href="?page=rolEditar&id=<?= $r["id_rol"] ?>" class="btn btn-white btn-sm px-3 border" title="Ajustar Permisos">
                                        <i class="bi bi-gear-fill text-primary"></i>
                                    </a>
                                    <a href="?page=rolEliminar&id=<?= $r["id_rol"] ?>" class="btn btn-white btn-sm px-3 border" title="Eliminar Rol" onclick="return confirm('¿Eliminar este rol? Asegúrate de que no haya usuarios vinculados.')">
                                        <i class="bi bi-trash3 text-danger"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-light border-0 py-3">
                <div class="row">
                    <div class="col-12 text-center text-md-start ps-md-4">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i> 
                            Los roles definen qué partes del <strong>Activo Digital</strong> (ERP) pueden operar los empleados.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php require "views/layouts/footer.php"; ?>