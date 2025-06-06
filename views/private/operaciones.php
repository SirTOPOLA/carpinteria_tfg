<?php
$rol = isset($_SESSION['usuario']['rol']) ? strtolower(trim($_SESSION['usuario']['rol'])) : '';
?>

<div id="content" class="container-fluid py-4">
    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-kanban-fill me-2"></i> Gestión de Tareas
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar tarea..." id="buscarTarea">
            </div>
            <?php if (in_array($rol, ['administrador', 'diseñador'])): ?>
                <a href="index.php?vista=registrar_tarea" class="btn btn-secondary">
                    <i class="bi bi-plus"></i> Nueva tarea
                </a>
            <?php endif; ?>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-custom align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-card-text me-1"></i>Descripción</th>
                            <th><i class="bi bi-person-badge-fill me-1"></i>Responsable</th>
                            <th><i class="bi bi-calendar-event me-1"></i>Inicio</th>
                            <th><i class="bi bi-calendar-check me-1"></i>Fin</th>
                            <th><i class="bi bi-flag-fill me-1"></i>Estado</th>
                            <th class="text-center"><i class="bi bi-tools me-1"></i>Acciones</th>
                        </tr>
                    </thead>

                    <tbody id="bodyTareas">
                        <!-- Cargado dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer row py-2 d-flex justify-content-between">
            <div id="resumenTareas" class="col-12 col-md-4 text-muted small text-center"></div>
            <div id="paginacionTareas" class="col-12 col-md-7 d-flex justify-content-center"></div>
        </div>
    </div>
</div>

<script>
    const inputBuscar = document.getElementById('buscarTarea');
    let paginaActual = 1;

    function cargarTareas(pagina = 1) {
        const termino = inputBuscar.value.trim();

        fetch('api/listar_operaciones.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `pagina=${pagina}&termino=${encodeURIComponent(termino)}`
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('bodyTareas').innerHTML = data.html;
                    document.getElementById('resumenTareas').innerText = data.resumen;
                    document.getElementById('paginacionTareas').innerHTML = data.paginacion;

                    document.querySelectorAll('.pagina-link').forEach(btn => {
                        btn.addEventListener('click', () => cargarTareas(btn.dataset.pagina));
                    });
                }
            });
    }

    function manejarEventosTareas() {
        document.getElementById("bodyTareas").addEventListener("click", function (e) {
            const eliminarBtn = e.target.closest(".btn-eliminar");
            if (eliminarBtn) {
                const id = eliminarBtn.dataset.id;
                if (confirm("¿Estás seguro de eliminar esta tarea?")) {
                    fetch(`controladores/tareas_produccion_eliminar.php`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id=${id}`
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                cargarTareas(paginaActual);
                            } else {
                                alert("Error al eliminar la tarea.");
                            }
                        });
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        cargarTareas();
        manejarEventosTareas();
        inputBuscar.addEventListener('input', () => cargarTareas(1));
    });
</script>