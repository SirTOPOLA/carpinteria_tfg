<?php
$stmt = $pdo->prepare("SELECT * FROM servicios");
$stmt->execute();
$servicios = $stmt->fetchAll();

?>


<div id="content" class="container-fliud">
    <!-- Card con tabla de roles -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
            <i class="bi bi-plug fs-4 me-2"></i>
            Gestión de Servicios
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar servicio..." id="buscador">
            </div>
            <a href="index.php?vista=registrar_servicios" class="btn btn-secondary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Nuevo servicio
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla" class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-box-seam me-1"></i>Nombre</th>
                            <th><i class="bi bi-grid-1x2 me-1"></i>Unidad</th>
                            <th><i class="bi bi-currency-dollar me-1"></i>Precio</th>
                            <th class="text-center"><i class="bi bi-check-circle me-1"></i>Activo</th>
                            <th class="text-center"><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>

                    </thead>
                    <tbody id="tbody">

                    </tbody>
                </table>

            </div>
        </div>
        <div class="card-footer row py-2 d-flex justify-content-between">
            <div id="resumen-paginacion" class="col-12 col-md-4 text-muted small  text-center "></div>
            <!-- Controles de paginación -->
            <div id="paginacion" class="col-12 col-md-7  d-flex justify-content-center "></div>
        </div>
    </div>

</div>

<script>

    const buscador = document.getElementById('buscador');
    let paginaActual = 1;

    //cargar las funciones al cargarse la pagina completamente
    document.addEventListener('DOMContentLoaded', () => {
        cargarDatos();
        clickPaginacion()
        manejarEventosAjaxTbody(); // Necesario cuando cargamos html por ajax
       // buscar()

    });

    function manejarEventosAjaxTbody() {
        document.getElementById("tbody").addEventListener("click", function (e) {
            //eliminar un registro de la fila por ID            
            if (e.target.closest(".btn-eliminar")) {
                const id = e.target.closest(".btn-eliminar").dataset.id;
                eliminar(id);
            }
            //cambiar estado 
            if (e.target.closest(".toggle-estado")) {
                const btn = e.target.closest(".toggle-estado");
                const id = btn.dataset.id;
                const estado = parseInt(btn.dataset.estado);
                cambiarEstado(id, estado);
            }


        });

    }

    async function eliminar(id) {
        if (confirm('¿Seguro que quieres eliminar este servicio?')) {
            try {
                const response = await fetch(`api/eliminar_servicios.php?id=${id}`, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Error al eliminar el servicio.');
                }
            } catch (error) {
                alert('Error en la petición.');
            }
        }
    }


    async function cambiarEstado(id, estado) {
        if (confirm(`¿Estás seguro de ${estado ? 'desactivar' : 'activar'} este servicio?`)) {
            try {
                const res = await fetch('api/toggle_estado_servicio.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, nuevo_estado: estado ? 0 : 1 })
                });

                const data = await res.json();
                if (data.success) {
                    location.reload(); // refresca para ver el cambio
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error en la petición.');
            }
        }

    }


    async function cargarDatos(pagina = 1, termino = '') {
        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('termino', termino);
        try {
            const res = await fetch('api/listar_servicios.php', {
                method: 'POST',
                body: formData
            })

            const data = await res.json();
            if (data.success) {
                document.getElementById('tbody').innerHTML = data.html;
                document.getElementById('paginacion').innerHTML = data.paginacion;
                document.getElementById('resumen-paginacion').textContent = data.resumen;
                paginaActual = pagina; // actualizar página actual
            } else {
                alert(data.message);
                console.log()
            }

        } catch (error) {
            alert('Error al cargar datos:', error);
            console.log(error)
        }

    }

    // Buscar
    function buscar() {
        buscador.addEventListener('input', async () => {
            paginaActual = 1;
            await cargarDatos(paginaActual, buscador.value.trim());
        });

    }
    // Manejar clics en paginación
    function clickPaginacion() {
        document.getElementById('paginacion').addEventListener('click', async e => {
            const btn = e.target.closest('.pagina-link');
            if (btn) {
                e.preventDefault();
                const nuevaPagina = parseInt(btn.dataset.pagina);
                if (!isNaN(nuevaPagina)) {
                    paginaActual = nuevaPagina;
                    await cargarDatos(paginaActual, buscador.value.trim());
                }
            }
        });

    }


</script>