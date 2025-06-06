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
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" aria-labelledby="modalCambiarEstadoLabel" aria-hidden="true">
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
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fotoProducto" class="form-label">Foto del Producto Terminado</label>
                        <input type="file" name="foto" id="fotoProducto" class="form-control" accept="image/*" required>
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





        });

    }
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
                    alert('Error al actualizar estado: ' + (data.message || ''));
                }
            })
            .catch(err => console.error('Error en la petición:', err));
    });

</script>