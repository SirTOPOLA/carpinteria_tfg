<?php require "views/layouts/header.php"; ?>
<?php require "views/layouts/sidebar.php"; ?>

<div class="container-fluid py-4 px-lg-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Nómina y Personal</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="?page=dashboard" class="text-decoration-none">Capital Humano</a></li>
                    <li class="breadcrumb-item active">Empleados</li>
                </ol>
            </nav>
        </div>
        <button type="button" class="btn btn-primary btn-lg rounded-pill shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalCrearEmpleado">
            <i class="bi bi-person-plus-fill me-2"></i> Registrar Empleado
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
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
                <tbody>
                    <?php foreach ($empleados as $e): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=<?= $e['nombre'].'+'.$e['apellido'] ?>&background=0d6efd&color=fff" class="rounded-3 me-3" width="40">
                                <div>
                                    <div class="fw-bold text-dark text-capitalize"><?= $e["nombre"]." ".$e["apellido"] ?></div>
                                    <div class="text-muted small">Doc: <?= $e["documento"] ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small">
                                <div class="text-dark"><i class="bi bi-envelope-at me-1"></i> <?= $e["correo"] ?></div>
                                <div class="text-muted"><i class="bi bi-telephone me-1"></i> <?= $e["telefono"] ?></div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill mb-1"><?= $e["rol_laboral"] ?></span>
                            <div class="fw-bold text-success small">CFA <?= number_format($e["salario_base"], 0) ?></div>
                        </td>
                        <td class="text-center">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input custom-switch" type="checkbox" role="switch" 
                                       <?= $e["activo"] ? 'checked' : '' ?> 
                                       onchange="toggleEstadoEmpleado(<?= $e['id_empleado'] ?>, this)">
                                <label class="d-block small mt-1 fw-bold <?= $e['activo'] ? 'text-success' : 'text-danger' ?>">
                                    <?= $e['activo'] ? 'ALTA' : 'BAJA' ?>
                                </label>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group shadow-sm rounded-3">
                                <button class="btn btn-white btn-sm border" onclick="verFicha(<?= htmlspecialchars(json_encode($e)) ?>)" title="Ver Ficha">
                                    <i class="bi bi-eye text-info"></i>
                                </button>
                                <button class="btn btn-white btn-sm border" onclick="editarEmpleado(<?= htmlspecialchars(json_encode($e)) ?>)" title="Editar">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <button class="btn btn-white btn-sm border" onclick="eliminarEmpleado(<?= $e['id_empleado'] ?>)" title="Eliminar">
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

<div class="modal fade" id="modalCrearEmpleado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><i class="bi bi-person-plus me-2"></i>Registro de Personal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="?page=empleadosGuardar" method="POST" class="p-3">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nombre</label>
                            <input type="text" name="nombre" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Apellido</label>
                            <input type="text" name="apellido" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Documento / DIP</label>
                            <input type="text" name="documento" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Cargo</label>
                            <input type="text" name="rol_laboral" class="form-control bg-light border-0" placeholder="Ej: Administrador" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Correo</label>
                            <input type="email" name="correo" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Teléfono</label>
                            <input type="text" name="telefono" class="form-control bg-light border-0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Salario Base</label>
                            <input type="number" step="0.01" name="salario_base" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Fecha Contratación</label>
                            <input type="date" name="fecha_contratacion" class="form-control bg-light border-0" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Dirección</label>
                            <textarea name="direccion" class="form-control bg-light border-0" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Guardar Colaborador</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarEmpleado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><i class="bi bi-pencil-square me-2"></i>Actualizar Datos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="?page=empleadosActualizar" method="POST" id="formEditarEmpleado" class="p-3">
                <input type="hidden" name="id_empleado" id="edit_id">
                <div class="modal-body">
                    <div class="row g-3" id="contenedorEditar">
                        </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-dark w-100 rounded-pill py-2">Actualizar Ficha</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalVerEmpleado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-info text-white border-0 py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-person-vcard me-2"></i>Ficha del Colaborador</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <img id="view_avatar" src="" class="rounded-circle shadow-sm mb-2" width="80">
                    <h4 id="view_nombre_completo" class="fw-bold text-dark mb-0"></h4>
                    <span id="view_cargo" class="badge bg-info-subtle text-info rounded-pill"></span>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="small text-muted d-block">Documento / DIP</label>
                        <span id="view_documento" class="fw-bold"></span>
                    </div>
                    <div class="col-6">
                        <label class="small text-muted d-block">Salario Base</label>
                        <span id="view_salario" class="fw-bold text-success"></span>
                    </div>
                    <div class="col-12 border-top pt-2">
                        <label class="small text-muted d-block"><i class="bi bi-envelope me-1"></i> Correo Electrónico</label>
                        <span id="view_correo" class="text-dark"></span>
                    </div>
                    <div class="col-12">
                        <label class="small text-muted d-block"><i class="bi bi-telephone me-1"></i> Teléfono</label>
                        <span id="view_telefono" class="text-dark"></span>
                    </div>
                    <div class="col-12 border-top pt-2">
                        <label class="small text-muted d-block"><i class="bi bi-geo-alt me-1"></i> Dirección</label>
                        <span id="view_direccion" class="text-dark italic"></span>
                    </div>
                    <div class="col-12 border-top pt-2">
                        <label class="small text-muted d-block"><i class="bi bi-calendar-check me-1"></i> Miembro desde</label>
                        <span id="view_fecha" class="text-dark"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light w-100 rounded-pill" data-bs-dismiss="modal">Cerrar Ficha</button>
            </div>
        </div>
    </div>
</div>

<script>
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});

// 1. Lógica para el Switch de Estado (AJAX)
function toggleEstadoEmpleado(id, element) {
    element.disabled = true;
    fetch(`?page=empleadoEstadoAjax&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                Toast.fire({ icon: 'success', title: 'Estado actualizado' });
                const label = element.nextElementSibling;
                label.textContent = element.checked ? 'ALTA' : 'BAJA';
                label.className = `d-block small mt-1 fw-bold ${element.checked ? 'text-success' : 'text-danger'}`;
            }
        })
        .finally(() => element.disabled = false);
}

// 2. Poblar Modal Editar
function editarEmpleado(data) {
    document.getElementById('edit_id').value = data.id_empleado;
    const campos = [
        {label: 'Nombre', name: 'nombre', val: data.nombre},
        {label: 'Apellido', name: 'apellido', val: data.apellido},
        {label: 'Documento', name: 'documento', val: data.documento},
        {label: 'Cargo', name: 'rol_laboral', val: data.rol_laboral},
        {label: 'Correo', name: 'correo', val: data.correo},
        {label: 'Teléfono', name: 'telefono', val: data.telefono},
        {label: 'Salario', name: 'salario_base', val: data.salario_base, type: 'number'},
        {label: 'Fecha Contratación', name: 'fecha_contratacion', val: data.fecha_contratacion, type: 'date'}
    ];
    
    let html = '';
    campos.forEach(c => {
        html += `<div class="col-md-6">
            <label class="form-label small fw-bold">${c.label}</label>
            <input type="${c.type || 'text'}" name="${c.name}" value="${c.val}" class="form-control bg-light border-0" required>
        </div>`;
    });
    
    document.getElementById('contenedorEditar').innerHTML = html;
    new bootstrap.Modal(document.getElementById('modalEditarEmpleado')).show();
}

// 3. Confirmación de Eliminación
function eliminarEmpleado(id) {
    Swal.fire({
        title: '¿Eliminar registro?',
        text: "Esta acción es irreversible en la nómina.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) window.location.href = `?page=empleadosEliminar&id=${id}`;
    });
}

// 4. Alertas de URL
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    if(params.has('success')) {
        Toast.fire({ icon: 'success', title: 'Operación exitosa' });
    }
});
function verFicha(data) {
    // 1. Asignar los valores a los elementos del modal
    document.getElementById('view_avatar').src = `https://ui-avatars.com/api/?name=${data.nombre}+${data.apellido}&background=0dcaf0&color=fff`;
    document.getElementById('view_nombre_completo').textContent = `${data.nombre} ${data.apellido}`;
    document.getElementById('view_cargo').textContent = data.rol_laboral;
    document.getElementById('view_documento').textContent = data.documento;
    document.getElementById('view_salario').textContent = `$${parseFloat(data.salario_base).toLocaleString()}`;
    document.getElementById('view_correo').textContent = data.correo;
    document.getElementById('view_telefono').textContent = data.telefono || 'No registrado';
    document.getElementById('view_direccion').textContent = data.direccion || 'Sin dirección registrada';
    document.getElementById('view_fecha').textContent = data.fecha_contratacion;

    // 2. Mostrar el modal manualmente
    const modalVer = new bootstrap.Modal(document.getElementById('modalVerEmpleado'));
    modalVer.show();
}
</script>

<?php require "views/layouts/footer.php"; ?>