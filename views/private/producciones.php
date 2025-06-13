<?php

$producciones = [];

try {
    $sql = "SELECT 
                p.id,
                pe.proyecto AS proyecto_nombre,
                p.fecha_inicio,
                p.fecha_fin,
                ep.nombre AS estado,
                e.nombre AS empleado_nombre,
                p.created_at,
                ep2.nombre AS estado_proyecto
            FROM producciones p
            LEFT JOIN pedidos pe ON p.solicitud_id = pe.id
            LEFT JOIN estados ep ON p.estado_id = ep.id
            LEFT JOIN empleados e ON p.responsable_id = e.id
            LEFT JOIN estados ep2 ON pe.estado_id = ep2.id
            ORDER BY p.created_at DESC";

    $stmt = $pdo->query($sql);
    $producciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM empleados  ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    echo "Error al cargar producciones: " . $e->getMessage();
}
?>

<div id="content" class="container-fluid py-4">

    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-boxes me-2"></i> Gestión de Producciones
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar producción..." id="buscador">
            </div>
            <a href="index.php?vista=registrar_producciones" class="btn btn-secondary">
                <i class="bi bi-plus-circle"></i> Nueva Producción
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-folder2-open me-1"></i>Proyecto</th>
                            <th><i class="bi bi-calendar-event me-1"></i>Inicio</th>
                            <th><i class="bi bi-calendar-check me-1"></i>Fin</th>
                            <th><i class="bi bi-flag-fill me-1"></i>Estado</th>
                            <th><i class="bi bi-person-fill-gear me-1"></i>Responsable</th>
                            <th><i class="bi bi-clock me-1"></i>Creado</th>
                            <!-- <th><i class="bi bi-cpu me-1"></i>Etapa</th> -->
                            <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>

                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php if (count($producciones) === 0): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-3">No se encontraron resultados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($producciones as $p): ?>
                                <tr>
                                    <td><?= $p['id'] ?></td>
                                    <td><?= htmlspecialchars($p['proyecto_nombre']) ?></td>
                                    <td><?= htmlspecialchars($p['fecha_inicio']) ?></td>
                                    <td><?= htmlspecialchars($p['fecha_fin']) ?></td>
                                    <td><?= htmlspecialchars($p['estado_proyecto']) ?></td>
                                    <td><?= htmlspecialchars($p['estado']) ?></td>
                                    <td><?= htmlspecialchars($p['empleado_nombre']) ?></td>
                                    <td><?= htmlspecialchars($p['created_at']) ?></td>
                                    <?php if (!in_array($rol, ['diseñador'])): ?>
                                        <td>
                                            <a href="index.php?vista=editar_producciones&id=<?= $p['id'] ?>"
                                                class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer row py-2 d-flex justify-content-between">
            <div id="resumen-paginacion" class="col-12 col-md-4 text-muted small  text-center "></div>
            <div id="paginacion" class="col-12 col-md-7  d-flex justify-content-center "></div>
        </div>
    </div>
</div>


<!--  materiales asignados al proyecto -->
<div class="modal fade" id="modalVerMateriales" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="bi bi-box-seam"></i> Materiales Asignados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="contenedorMateriales"></div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Cambiar Estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" aria-labelledby="modalCambiarEstadoLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form id="formCambiarEstado" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCambiarEstadoLabel">Cambiar Estado de Producción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="produccionId" name="id">

                    <div class="mb-3">
                        <label for="nuevoEstado" class="form-label">Nuevo Estado</label>
                        <select class="form-select" name="estado" id="nuevoEstado" required>
                            <option value="">Seleccione estado</option>
                            <option value="finalizado">Finalizada</option>
                           <!--  <option value="en_proceso">En proceso</option> -->
                            <!--  <option value="cancelado">Cancelado</option> -->
                        </select>
                    </div>

                    <!-- Checkbox para stock -->
                    <div class="form-check d-none" id="checkStockContainer">
                        <input class="form-check-input" type="checkbox" id="deseaStock">
                        <label class="form-check-label" for="deseaStock">Deseo actualizar stock</label>
                    </div>

                    <!-- Campo de stock (opcional) -->
                    <div class="mb-3 d-none" id="stockContainer">
                        <label for="stockFinal" class="form-label">Cantidad a Stock</label>
                        <input type="number" name="stock" id="stockFinal" class="form-control" min="1"
                            placeholder="Cantidad en unidades">
                    </div>
 
                </div>


                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Modal Registrar Avance -->
<div class="modal fade" id="modalRegistrarAvance" tabindex="-1" aria-labelledby="avanceLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formRegistrarAvance" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="bi bi-clipboard-plus"></i> Registrar Avance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="produccion_id" id="produccion_id">

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label for="descripcion_avance" class="form-label">Descripción del Avance</label>
                        <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                    </div>

                    <!-- Imagen -->
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen (opcional)</label>
                        <input type="file" name="imagen" class="form-control" accept="image/*">
                    </div>

                    <!-- Porcentaje de avance -->
                    <div class="mb-3">
                        <label for="porcentaje" class="form-label">Progreso del Pedido: 
                            <span id="valorPorcentaje" class="badge bg-primary">0%</span>
                        </label>
                        <input type="range" name="porcentaje" id="porcentaje" class="form-range" min="0" max="100" step="1" value="0" oninput="actualizarPorcentaje(this.value)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info"><i class="bi bi-check2-circle"></i> Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>



<!-- ---- modal de registro de tareas ------- -->
<!-- Modal para registrar tarea -->
<div class="modal fade" id="modalRegistrarTarea" tabindex="-1" aria-labelledby="tituloRegistrarTarea"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="formRegistrarTarea">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="tituloRegistrarTarea">Registrar Nueva Tarea</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="produccion_id" id="produccion_id_tarea">

                    <div class="mb-3">
                        <label for="descripcion_tarea" class="form-label">Descripción de la Tarea</label>
                        <textarea class="form-control" name="descripcion" id="descripcion_tarea" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="responsable_tarea" class="form-label">Responsable</label>
                        <select class="form-select" name="responsable_id" id="responsable_tarea" required>
                            <option value="">Seleccione un empleado</option>
                            <?php foreach ($empleados as $prod): ?>
                                <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nombre']) ?>
                                    <?= htmlspecialchars($prod['apellido']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_inicio_tarea" class="form-label">Fecha de Inicio</label>
                        <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio_tarea" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_fin_tarea" class="form-label">Fecha de Fin</label>
                        <input type="date" class="form-control" name="fecha_fin" id="fecha_fin_tarea" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar Tarea</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para mostrar porcentaje dinámico -->
<script>

    /* --------- Avances de proyecto ----------- */
/* barra de progreso de los avances
 */
 
let porcentajeRestante = 100;

function cargarProgresoActual(produccionId) {
    fetch('api/obtener_progreso.php?produccion_id=' + produccionId)
        .then(response => response.json())
        .then(data => {
            const totalActual = parseInt(data.total_porcentaje);
            const range = document.getElementById('porcentaje');
            const badge = document.getElementById('valorPorcentaje');

            // Establecer los límites del rango
            range.min = totalActual;
            range.max = 100;
            range.value = totalActual;

            actualizarPorcentaje(range.value);
 
        });
}

document.getElementById('modalRegistrarAvance').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget; // El botón que abrió el modal
    const produccionId = button?.getAttribute('data-produccion-id');

    if (produccionId) {
        // Asigna el ID al input hidden
        document.getElementById('produccion_id').value = produccionId;

        // Llama a la función que carga el porcentaje actual
        cargarProgresoActual(produccionId);
    }
});

function actualizarPorcentaje(valor) {
    const badge = document.getElementById('valorPorcentaje');
    const valorNum = parseInt(valor);

    badge.innerText = valorNum + '%';

    let bg = 'bg-danger';
    if (valorNum >= 75) bg = 'bg-success';
    else if (valorNum >= 40) bg = 'bg-warning';

    badge.className = 'badge ' + bg;
}


/* fin modal de edicion de avances */

    const buscador = document.getElementById('buscador');
    let paginaActual = 1;

    //cargar las funciones al cargarse la pagina completamente
    document.addEventListener('DOMContentLoaded', () => {
        cargarDatos();
        clickPaginacion()
        manejarEventosAjaxTbody(); // Necesario cuando cargamos html por ajax
        // buscar() 
        const estadoSelect = document.getElementById('nuevoEstado');

        const checkStockContainer = document.getElementById('checkStockContainer');
        const checkboxStock = document.getElementById('deseaStock');
        const stockContainer = document.getElementById('stockContainer');
        const stockInput = document.getElementById('stockFinal');

        
        estadoSelect.addEventListener('change', () => {
            const estado = estadoSelect.value;

            if (estado === 'finalizado') {
                // Mostrar los checkboxes de opciones
                checkStockContainer.classList.remove('d-none');
               // checkImagenContainer.classList.remove('d-none');

                // Ocultar campos por defecto hasta que se activen los checkbox
                stockContainer.classList.add('d-none'); 

                // Limpiar valores y requerimientos
                stockInput.value = '';
                stockInput.removeAttribute('required');
                checkboxStock.checked = false; 

            } else {
                // Si el estado no es 'finalizado', ocultamos todo
                checkStockContainer.classList.add('d-none');
                stockContainer.classList.add('d-none');
                checkboxStock.checked = false;
                stockInput.value = '';
                stockInput.removeAttribute('required');
 
            }
        });

        // Mostrar/ocultar campo stock
        checkboxStock.addEventListener('change', () => {
            if (checkboxStock.checked) {
                stockContainer.classList.remove('d-none');
                stockInput.setAttribute('required', 'required');
            } else {
                stockContainer.classList.add('d-none');
                stockInput.value = '';
                stockInput.removeAttribute('required');
            }
        });

       

        /* avances del pedido segun fases de la produccion ----- */
        document.addEventListener('click', function (e) {
            if (e.target.closest('.registrar-avance-btn')) {
                const btn = e.target.closest('.registrar-avance-btn');
                const produccionId = btn.dataset.id;
                document.getElementById('produccion_id').value = produccionId;
            }
        });

        document.getElementById('formRegistrarAvance')?.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            const res = await fetch('api/guardar_avance.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            if (data.success) {
                alert('Avance registrado con éxito');
                document.getElementById('formRegistrarAvance').reset();
                bootstrap.Modal.getInstance(document.getElementById('modalRegistrarAvance')).hide();
                // Opcional: recargar listado o mostrar avance
            } else {
                alert('Error al registrar el avance');
            }
        });


    });

    function manejarEventosAjaxTbody() {
        document.getElementById("tbody").addEventListener("click", function (e) {
            //eliminar un registro de la fila por ID            
            if (e.target.closest(".btn-eliminar")) {
                const id = e.target.closest(".btn-eliminar").dataset.id;
                eliminar(id);
            }


            /* ----- modal de listado de materialesasociados al pedido --------- */
            if (e.target.closest('.ver-materiales-btn')) {
                const btn = e.target.closest('.ver-materiales-btn');
                const produccionId = btn.dataset.id;
                const proyecto = btn.dataset.proyecto;
                document.querySelector('#modalVerMateriales .modal-title').innerHTML = `<i class="bi bi-box-seam"></i> Materiales: ${proyecto}`;

                const contenedor = document.getElementById('contenedorMateriales');
                contenedor.innerHTML = '<div class="text-center my-3"><div class="spinner-border text-info"></div></div>';

                fetch('api/obtener_materiales_pedido.php', {
                    method: 'POST',
                    body: new URLSearchParams({ id: produccionId })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            contenedor.innerHTML = data.html;
                        } else {
                            contenedor.innerHTML = `<div class="alert alert-warning">${data.message}</div>`;
                        }
                    })
                    .catch(err => {
                        contenedor.innerHTML = `<div class="alert alert-danger">Error al cargar materiales</div>`;
                    });
            }

            /* -------registrar tareas ------------ */
            if (e.target.closest(".registrar-tarea-btn")) {
                const btn = e.target.closest(".registrar-tarea-btn");
                const produccionId = btn.dataset.id;
                const proyecto = btn.dataset.proyecto;

                document.getElementById('produccion_id_tarea').value = produccionId;
                document.getElementById('tituloRegistrarTarea').textContent = `Registrar Tarea para "${proyecto}"`;
                document.getElementById('produccion_id').value = produccionId
            }


        });

    }

    document.getElementById('formRegistrarTarea').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = e.target;
        const datos = new FormData(form);

        fetch('api/guardar_operacion.php', {
            method: 'POST',
            body: datos
        })
            .then(res => res.text())
            .then(text => {
                try {
                    const resp = JSON.parse(text);
                    //console.log(resp);
                    if (resp.success) {
                        alert('Tarea registrada correctamente');
                        form.reset();
                        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalRegistrarTarea'));
                        modal.hide();
                    } else {
                        form.reset();
                        alert(resp.message || 'Error al registrar la tarea');
                    }
                } catch (e) {
                    //console.error("Respuesta no válida:", text);
                    alert('Error inesperado en el servidor');
                }
            })
            .catch(err => console.error(err));

    });


    moverMaterial()
    function moverMaterial() {
        document.addEventListener('click', function (e) {
            if (e.target.closest('.mover-material-btn')) {
                const btn = e.target.closest('.mover-material-btn');
                const materialId = btn.dataset.materialId;
                const produccionId = btn.dataset.produccionId;

                const cantidad = document.querySelector(`.cantidad-mover[data-material-id="${materialId}"]`)?.value;
                const motivo = document.querySelector(`.motivo-mover[data-material-id="${materialId}"]`)?.value;
                const tipo = document.querySelector(`.tipo-movimiento[data-material-id="${materialId}"]`)?.value;

                if (!cantidad || cantidad <= 0 || !motivo) {
                    alert('Por favor, completa todos los campos correctamente.');
                    return;
                }

                const formData = new FormData();
                formData.append('material_id', materialId);
                formData.append('produccion_id', produccionId);
                formData.append('cantidad', cantidad);
                formData.append('motivo', motivo);
                formData.append('tipo', tipo); // clave aquí

                fetch('api/guardar_movimiento.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Movimiento registrado correctamente');
                            // recargar materiales o actualizar stock si deseas
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error al realizar el movimiento');
                    });
            }
            /* ------ cambo de boton e icon segun tipo de movimiento --------------- */

            if (e.target.closest('.tipo-movimiento')) {
                const select = e.target.closest('.tipo-movimiento');
                select.addEventListener('change', function () {
                    const materialId = this.dataset.materialId;
                    const tipo = this.value;

                    const btn = document.querySelector(`.mover-material-btn[data-material-id="${materialId}"]`);
                    const icono = btn.querySelector('.icono-movimiento');

                    // Cambiar clases de color del botón
                    btn.classList.remove('btn-outline-primary', 'btn-outline-success');
                    if (tipo === 'salida') {
                        btn.classList.add('btn-outline-primary'); // azul
                        icono.className = 'bi bi-arrow-right-circle icono-movimiento'; // flecha derecha
                    } else {
                        btn.classList.add('btn-outline-success'); // verde
                        icono.className = 'bi bi-arrow-left-circle icono-movimiento'; // flecha izquierda
                    }
                });


            }



        });
        /* ---------------- recalcular el valor disponible de material -------- */
        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('tipo-movimiento')) {
                const materialId = e.target.dataset.materialId;
                const tipo = e.target.value;

                const cantidadInput = document.querySelector(`.cantidad-mover[data-material-id='${materialId}']`);
                const motivoInput = document.querySelector(`.motivo-mover[data-material-id='${materialId}']`);
                const moverBtn = document.querySelector(`.mover-material-btn[data-material-id='${materialId}']`);
                const restanteSpan = document.querySelector(`.restante[data-material-id='${materialId}']`);

                const restante = parseInt(restanteSpan.dataset.restante);

                if (tipo === 'entrada') {
                    cantidadInput.disabled = false;
                    motivoInput.disabled = false;
                    moverBtn.disabled = false;
                    cantidadInput.removeAttribute('max');
                } else {
                    const disable = restante <= 0;
                    cantidadInput.disabled = disable;
                    motivoInput.disabled = disable;
                    moverBtn.disabled = disable;
                    cantidadInput.setAttribute('max', restante);
                }
            }
        });

        // Inicializar correctamente al cargar
        document.querySelectorAll('.tipo-movimiento').forEach(select => {
            select.dispatchEvent(new Event('change'));
        });



    }


    async function eliminar(id) {
        if (confirm('¿Seguro que quieres eliminar esta produccion?')) {
            try {
                const response = await fetch(`api/eliminar_produccion.php?id=${id}`, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Error al eliminar el produccion.');
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
            const res = await fetch('api/listar_producciones.php', {
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

    // Abre el modal y carga los datos para cambiar estado 
    document.addEventListener('click', function (e) {
        if (e.target.closest('.cambiar-estado-btn')) {
            const btn = e.target.closest('.cambiar-estado-btn');

            // Asigna datos al formulario
            document.getElementById('produccionId').value = btn.dataset.id;
            document.getElementById('nuevoEstado').value = btn.dataset.estado;
        }
    });

    // Enviar formulario de cambio de estado
    document.getElementById('formCambiarEstado').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('api/actualizar_estado_produccion.php', {
            method: 'POST',
            body: formData
        })
            .then(resp => resp.json())
            .then(data => {
                if (data.success) {
                    // Cerrar modal
                    bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado')).hide();
                    // Recargar tabla o volver a hacer fetch
                    location.reload();
                } else {
                    alert('Error al actualizar estado: ' + (data.message || '') + '\n' + (data.error || ''));
                   // alert('Error al actualizar estado: ' + (data.message || ''));
                }
            })
            .catch(err => console.error('Error en la petición:', err));
    });

</script>