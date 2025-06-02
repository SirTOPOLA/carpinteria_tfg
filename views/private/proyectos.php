<?php


$sql = "SELECT * FROM proyectos ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql = "SELECT 
                p.id,
                pr.nombre AS nombre_proyecto,
                e.nombre AS responsable
            FROM producciones p
            INNER JOIN proyectos pr ON p.proyecto_id = pr.id
            INNER JOIN empleados e ON p.responsable_id = e.id
            ORDER BY pr.nombre ASC";
$stmt = $pdo->query($sql);
$producciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT id, nombre, stock_actual, stock_minimo FROM materiales ORDER BY nombre ASC");
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>



<div id="content" class="container-fluid py-4">
    <!-- Contenedor -->
    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-kanban-fill me-2"></i> Lista de Proyectos
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar proyecto..." id="buscador-proyectos">
            </div>
            <a href="index.php?vista=registrar_proyectos" class="btn btn-secondary">
                <i class="bi bi-plus"> </i>Nuevo Proyecto
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-card-heading me-1"></i>Nombre</th>
                            <th><i class="bi bi-file-text me-1"></i>Descripción</th>
                            <th><i class="bi bi-flag-fill me-1"></i>Estado</th>
                            <th><i class="bi bi-calendar-event me-1"></i>Inicio</th>
                            <th><i class="bi bi-calendar-check me-1"></i>Entrega</th>
                            <th><i class="bi bi-clock-history me-1"></i>Creado</th>
                            <th class="text-center"><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php if (count($proyectos) === 0): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">No se encontraron proyectos.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($proyectos as $p): ?>
                                <tr>
                                    <td><?= $p['id'] ?></td>
                                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                                    <td><?= htmlspecialchars($p['descripcion']) ?></td>
                                    <td><?= ucfirst($p['estado']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($p['fecha_inicio'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($p['fecha_entrega'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($p['creado_en'])) ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-success" data-bs-toggle="modal"
                                            data-bs-target="#modalMovimiento">
                                            <i class="bi bi-plus-circle"></i> Registrar Movimiento
                                        </button>

                                        <a href="index.php?vista=registrar_movimientos&id=<?= $p['id'] ?>"
                                            class="btn btn-sm btn-outline-secondary" title="Asignar material">
                                            <i class="bi bi-files"></i>
                                        </a>
                                        <a href="index.php?vista=editar_proyectos&id=<?= $p['id'] ?>"
                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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

<!-- Modal para registrar movimiento -->
<div class="modal fade" id="modalMovimiento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-dark text-white rounded-top-4 d-flex align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-arrow-left-right me-2 fs-4"></i>
                        Registrar Movimiento
                    </h5>
                </div>

                <div class="card-body">
                    <form method="POST" id="formMovimiento" class="needs-validation" novalidate>

                        <!-- Selección de producción -->
                        <div class="mb-3">
                            <label for="produccion_id" class="form-label">
                                <i class="bi bi-hammer text-danger me-1"></i> Producción Asociada
                                <span class="text-danger">*</span>
                            </label>
                            <select name="produccion_id" id="produccion_id" class="form-select" required>
                                <option value="">Seleccione una producción</option>
                                <?php foreach ($producciones as $prod): ?>
                                    <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nombre_proyecto']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Contenedor dinámico de materiales -->
                        <div id="materialesContainer"></div>

                        <button type="button" class="btn btn-outline-success mb-3" id="btnAgregarMaterial">
                            <i class="bi bi-plus-circle"></i> Agregar Material
                        </button>

                        <!-- Observaciones generales -->
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">
                                <i class="bi bi-chat-left-dots text-secondary me-1"></i> Observaciones generales
                            </label>
                            <textarea name="observaciones" class="form-control" rows="3"></textarea>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-between">
                            <a href="index.php?vista=movimientos" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save2"></i> Guardar Movimiento
                            </button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
    <!-- Solo asegúrate que el botón tenga id="btnGuardarMovimiento" -->
</div>
<!-- Modal Cambiar Estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" aria-labelledby="modalCambiarEstadoLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form id="formCambiarEstado">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Estado de proyecto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                <input type="hidden" id="tipoCambio" name="tipo">
    
                <input type="hidden" id="proyectoId" name="id">
                    <div class="mb-3">
                        <label for="nuevoEstado" class="form-label">Nuevo Estado</label>
                        <select class="form-select" name="estado" id="nuevoEstado" required>
                            <option value="">Seleccione estado</option> 
                            <option value="en_produccion">En Producción</option> 
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
    document.addEventListener("DOMContentLoaded", () => {
        cargarDatos();
        clickPaginacion()
        manejarEventosAjaxTbody(); // Necesario cuando cargamos html por ajax
        // buscar()
        const stockMateriales = <?php echo json_encode($materiales, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

        const materialesContainer = document.getElementById("materialesContainer");
        const btnAgregarMaterial = document.getElementById("btnAgregarMaterial");
        let grupoIndex = 0;

        const obtenerMaterialesDisponibles = (modoActual, indexActual) => {
            const seleccionados = new Set();

            document.querySelectorAll('.grupo-material').forEach((grupo, index) => {
                if (index !== indexActual) {
                    const tipoSel = grupo.querySelector('[name="tipo[]"]');
                    const matSel = grupo.querySelector('[name="material_id[]"]');
                    if (!tipoSel || !matSel) return;

                    const tipo = tipoSel.value;
                    const material = matSel.value;

                    if (tipo === 'entrada') seleccionados.add(material);
                }
            });

            return stockMateriales.filter(mat =>
                modoActual !== 'entrada' || !seleccionados.has(String(mat.id))
            );
        };

        const actualizarOpcionesMaterial = (select, tipoMovimiento, index) => {
            const materiales = obtenerMaterialesDisponibles(tipoMovimiento, index);
            select.innerHTML = `<option value="">Seleccione material</option>`;
            materiales.forEach(mat => {
                select.innerHTML += `<option value="${mat.id}">${mat.nombre}</option>`;
            });
        };

        const crearGrupoMaterial = () => {
            const index = grupoIndex++;
            const div = document.createElement("div");
            div.className = "row g-3 mb-3 border p-3 rounded bg-light grupo-material";

            div.innerHTML = `
                <div class="col-md-4">
                    <label class="form-label">Tipo de Movimiento <span class="text-danger">*</span></label>
                    <select name="tipo[]" class="form-select tipo" required>
                        <option value="">Seleccione tipo</option>
                        <option value="entrada">Entrada</option>
                        <option value="salida">Salida</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Material <span class="text-danger">*</span></label>
                    <select name="material_id[]" class="form-select material" required disabled>
                        <option value="">Seleccione tipo primero</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Cantidad <span class="text-danger">*</span></label>
                    <input type="number" name="cantidad[]" class="form-control cantidad" min="1" required disabled>
                    <div class="form-text text-danger info-stock"></div>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm btnEliminarGrupo">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            `;

            materialesContainer.appendChild(div);

            const tipoSel = div.querySelector(".tipo");
            const matSel = div.querySelector(".material");
            const cantidadInput = div.querySelector(".cantidad");
            const infoStock = div.querySelector(".info-stock");

            tipoSel.addEventListener("change", () => {
                matSel.disabled = false;
                actualizarOpcionesMaterial(matSel, tipoSel.value, index);
                cantidadInput.value = "";
                cantidadInput.disabled = true;
                infoStock.textContent = "";
                cantidadInput.setCustomValidity("");
            });

            matSel.addEventListener("change", () => {
                cantidadInput.disabled = false;
                const material = stockMateriales.find(m => m.id == matSel.value);
                const tipo = tipoSel.value;

                if (!material) return;

                if (tipo === "salida") {
                    const maxPermitido = material.stock_actual - material.stock_minimo;
                    cantidadInput.max = maxPermitido;
                    infoStock.innerHTML = `
                        Stock actual: <strong>${material.stock_actual}</strong><br>
                        Stock mínimo: <strong>${material.stock_minimo}</strong><br>
                        Máximo permitido para salida: <strong>${maxPermitido}</strong>
                    `;
                } else {
                    cantidadInput.removeAttribute("max");
                    infoStock.textContent = "";
                }

                cantidadInput.setCustomValidity("");
            });

            cantidadInput.addEventListener("input", () => {
                const tipo = tipoSel.value;
                const material = stockMateriales.find(m => m.id == matSel.value);
                const val = parseInt(cantidadInput.value);

                if (!material || isNaN(val)) {
                    cantidadInput.setCustomValidity("");
                    return;
                }

                if (tipo === "salida") {
                    const maxPermitido = material.stock_actual - material.stock_minimo;
                    if (val > maxPermitido) {
                        infoStock.innerHTML += `<br><span class="text-danger fw-bold">⚠ La cantidad excede el stock permitido</span>`;
                        cantidadInput.setCustomValidity("Cantidad excede el stock permitido.");
                    } else {
                        infoStock.innerHTML = `
                            Stock actual: <strong>${material.stock_actual}</strong><br>
                            Stock mínimo: <strong>${material.stock_minimo}</strong><br>
                            Máximo permitido para salida: <strong>${maxPermitido}</strong>
                        `;
                        cantidadInput.setCustomValidity("");
                    }
                }
            });

            div.querySelector(".btnEliminarGrupo").addEventListener("click", () => {
                div.remove();
                actualizarTodosLosSelects();
            });
        };

        const actualizarTodosLosSelects = () => {
            document.querySelectorAll('.grupo-material').forEach((grupo, index) => {
                const tipoSel = grupo.querySelector('[name="tipo[]"]');
                const matSel = grupo.querySelector('[name="material_id[]"]');
                if (!tipoSel || !matSel) return;

                actualizarOpcionesMaterial(matSel, tipoSel.value, index);
            });
        };

        btnAgregarMaterial.addEventListener("click", () => {
            crearGrupoMaterial();
        });

        // Uno por defecto
        crearGrupoMaterial();

        // Envío
        const form = document.getElementById("formMovimiento");
        form.addEventListener("submit", async function (e) {
            e.preventDefault();

            if (!form.checkValidity()) {
                form.classList.add("was-validated");
                return;
            }

            const formData = new FormData(form);

            try {
                const response = await fetch("api/guardar_movimientos.php", {
                    method: "POST",
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    alert("✅ Movimiento registrado correctamente.");
                    window.location.href = "index.php?vista=movimientos";
                } else {
                    alert("⚠ Error: " + data.message);
                }

            } catch (err) {
                console.error(err);
                alert("❌ Error en el servidor.");
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




        });

    }

    async function eliminar(id) {
        if (confirm('¿Seguro que quieres eliminar este proyecto?')) {
            try {
                const response = await fetch(`api/eliminar_proyecto.php?id=${id}`, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Error al eliminar el proyecto.');
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
            const res = await fetch('api/listar_proyectos.php', {
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
            document.getElementById('proyectoId').value = btn.dataset.id;
            document.getElementById('nuevoEstado').value = btn.dataset.estado;
            document.getElementById('tipoCambio').value = btn.dataset.tipo; // tipo: solicitud, proyecto, produccion
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