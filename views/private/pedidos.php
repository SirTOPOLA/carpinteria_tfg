<?php
$sql = "SELECT p.*,
        c.nombre AS cliente    
        FROM pedidos p
        INNER JOIN clientes c ON p.cliente_id = c.id 
         ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetch(PDO::FETCH_ASSOC);


$rol = isset($_SESSION['usuario']['rol']) ? strtolower(trim($_SESSION['usuario']['rol'])) : '';
?>


<div id="content" class="container-fluid py-4">

    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-kanban-fill me-2"></i> Gestión de Pedidos
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar pedido..." id="buscador">
            </div>
            <?php if (in_array($rol, ['administrador', 'diseñador'])): ?>
                <a href="index.php?vista=registrar_pedidos" class="btn btn-secondary">

                    <i class="bi bi-plus"> </i>Nuevo pedido</a>
            <?php endif; ?>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-file-text me-1"></i>Cliente</th>
                            <th><i class="bi bi-card-heading me-1"></i>Proyecto</th>
                            <th><i class="bi bi-flag-fill me-1"></i>Descripción</th>
                            <th><i class="bi bi-calendar-event me-1"></i>Creado</th>
                            <th><i class="bi bi-calendar-check me-1"></i>Estado</th>
                            <th><i class="bi bi-clock-history me-1"></i>Coste</th>
                            <th class="text-center"><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php if (!empty($pedidos) === 0): ?>

                            <?php foreach ($pedidos as $p): ?>
                                <tr>
                                    <td><?= $p['id'] ?></td>
                                    <td><?= htmlspecialchars($p['proyecto']) ?></td>
                                    <td><?= htmlspecialchars($p['cliente']) ?></td>
                                    <td><?= htmlspecialchars($p['descripcion']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($p['fecha_solicitud'])) ?></td>
                                    <td><?= ucfirst($p['estado']) ?></td>
                                    <td>XAF <?= number_format($p['estimacion_total'], 1) ?></td>
                                    <td class="text-center">
                                        <?php if (in_array($rol, ['administrador', 'diseñador'])): ?>
                                            <a href="index.php?vista=destalles_pedidos&id=<?= $p['id'] ?>"
                                                class="btn btn-sm btn-outline-info" title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="index.php?vista=editar_pedidos&id=<?= $p['id'] ?>"
                                                class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        <?php endif; ?>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">No se encontraron pedidos.</td>
                            </tr>
                        <?php endif; ?>
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
<!-- Modal Cambiar Estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" aria-labelledby="modalCambiarEstadoLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form id="formCambiarEstado">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Estado de Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="pedidoId" name="id">
                    <div class="mb-3">
                        <label for="nuevoEstado" class="form-label">Nuevo Estado</label>
                        <select class="form-select" name="estado" id="nuevoEstado" required>
                            <option value="">Seleccione estado</option>
                            <option value="aprobado">Aprobado</option>

                        </select>
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



        });

    }

    async function eliminar(id) {
        if (confirm('¿Seguro que quieres eliminar este pedido?')) {
            try {
                const response = await fetch(`api/eliminar_pedidos.php?id=${id}`, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Error al eliminar el pedido.');
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
            const res = await fetch('api/listar_pedidos.php', {
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
        document.getElementById('paginacion').addEventListener('click', async (e) => {
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
            document.getElementById('pedidoId').value = btn.dataset.id;
            document.getElementById('nuevoEstado').value = btn.dataset.estado;

        }
    });

    // Enviar formulario de cambio de estado
    document.getElementById('formCambiarEstado').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('api/actualizar_estado_pedido.php', {
            method: 'POST',
            body: formData
        })
            .then(async resp => {
                const contentType = resp.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return resp.json();
                } else {
                    const text = await resp.text();
                    throw new Error('Respuesta no JSON: ' + text);
                }
            })
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado')).hide();
                    location.reload();
                } else {
                    alert('Error al actualizar estado: ' + (data.message || ''));
                }
            })
            .catch(err => console.error('Error en la petición:', err));

    });






</script>