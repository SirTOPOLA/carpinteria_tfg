<?php require "views/layouts/header.php"; ?>
<?php require "views/layouts/sidebar.php"; ?>

 
    <div class="container-fluid py-4 px-lg-5">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1">Nómina y Personal</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="?page=dashboard" class="text-decoration-none">Capital
                                Humano</a></li>
                        <li class="breadcrumb-item active">Empleados</li>
                    </ol>
                </nav>
            </div>

            <a href="?page=empleadoCrear"
                class="btn btn-primary btn-lg rounded-pill shadow-sm px-4 d-flex align-items-center">
                <i class="bi bi-person-plus-fill me-2"></i>
                <span>Registrar Empleado</span>
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="text-muted small fw-bold text-uppercase">Directorio Operativo</span>
                    </div>
                    <div class="col-auto">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-funnel"></i></span>
                            <select class="form-select bg-light border-0">
                                <option>Todos los puestos</option>
                                <option>Carpintero</option>
                                <option>Administración</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">Colaborador</th>
                            <th>Contacto</th>
                            <th>Cargo / Salario</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php foreach ($empleados as $e): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <img src="https://ui-avatars.com/api/?name=<?= $e["nombre"] . '+' . $e["apellido"] ?>&background=0d6efd&color=fff"
                                                class="rounded-3" width="40">
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark text-capitalize">
                                                <?= $e["nombre"] . " " . $e["apellido"] ?></div>
                                            <div class="text-muted small">Doc: <?= $e["documento"] ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="small">
                                        <div class="text-dark"><i class="bi bi-envelope-at me-1"></i> <?= $e["correo"] ?>
                                        </div>
                                        <div class="text-muted"><i class="bi bi-telephone me-1"></i> <?= $e["telefono"] ?>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill mb-1">
                                            <?= $e["rol_laboral"] ?>
                                        </span>
                                        <div class="fw-bold text-success small">
                                            $<?= number_format($e["salario_base"], 2) ?>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input custom-switch" type="checkbox" role="switch"
                                            <?= $e["activo"] ? 'checked' : '' ?>
                                            onchange="window.location.href='?page=empleadoEstado&id=<?= $e['id_empleado'] ?>'">
                                        <label
                                            class="d-block small mt-1 <?= $e["activo"] ? 'text-success' : 'text-danger' ?> fw-bold">
                                            <?= $e["activo"] ? 'ALTA' : 'BAJA' ?>
                                        </label>
                                    </div>
                                </td>

                                <td class="text-end pe-4">
                                    <div class="btn-group shadow-sm rounded-3">
                                        <a href="?page=empleadoPerfil&id=<?= $e["id_empleado"] ?>"
                                            class="btn btn-white btn-sm px-3 border" title="Ficha Técnica">
                                            <i class="bi bi-eye text-info"></i>
                                        </a>
                                        <a href="?page=empleadoEditar&id=<?= $e["id_empleado"] ?>"
                                            class="btn btn-white btn-sm px-3 border" title="Editar Datos">
                                            <i class="bi bi-pencil text-primary"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-0 py-3">
                <small class="text-muted ps-2 italic">
                    <i class="bi bi-info-circle me-1"></i> El salario base no incluye bonificaciones por proyecto.
                </small>
            </div>
        </div>
    </div>
 

<style>
    /* Estilo para el switch profesional */
    .custom-switch {
        width: 2.5em !important;
        height: 1.25em !important;
        cursor: pointer;
    }

    .custom-switch:checked {
        background-color: #198754 !important;
        border-color: #198754 !important;
    }
</style>

<?php require "views/layouts/footer.php"; ?>