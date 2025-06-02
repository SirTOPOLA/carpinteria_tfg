<?php



// Obtener lista de empleados
$stmt = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo  FROM empleados ORDER BY id");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Obtener lista de servicios
$stmt = $pdo->query("SELECT * FROM servicios ");
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de clientes
$stmt = $pdo->query("SELECT * FROM clientes ");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de materiales con su precio unitario 
$stmt = $pdo->query("SELECT dc.material_id,
                            dc.precio_unitario,
                            m.nombre AS nombre_material,
                            m.stock_actual, 
                            m.stock_minimo
                        FROM detalles_compra dc
                        INNER JOIN (
                            SELECT material_id, MAX(precio_unitario) AS max_precio
                            FROM detalles_compra
                            GROUP BY material_id
                        ) AS max_dc ON dc.material_id = max_dc.material_id AND dc.precio_unitario = max_dc.max_precio
                        INNER JOIN materiales m ON dc.material_id = m.id
                        WHERE m.stock_actual > m.stock_minimo

                         ");
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<div id="content" class="container-fluid py-4">
    <div class="card border-0 shadow rounded-4 col-lg-10 mx-auto">
        <div class="card-header bg-warning text-white rounded-top-4 py-3">
            <h5 class="mb-0"><i class="bi bi-cart-plus-fill me-2"></i>Registrar Pedido</h5>
        </div>

        <div class="card-body px-4 py-4">
            <form id="form" method="POST" class="row g-4 needs-validation" novalidate>

                <!-- SECCIÓN: Información General -->
                <div class="border-bottom pb-3 mb-4">
                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-vcard-fill me-2"></i>Información del
                        Cliente y Proyecto</h6>

                    <div class="row g-3">
                        <!-- Cliente -->
                        <div class="col-md-6">
                            <label for="clientes" class="form-label fw-semibold"><i
                                    class="bi bi-person-fill me-1"></i>Cliente <span
                                    class="text-danger">*</span></label>
                            <select name="responsable_id" id="clientes" class="form-select" required>
                                <option value="">Seleccione un cliente</option>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?= htmlspecialchars($cliente['id']) ?>">
                                        <?= htmlspecialchars($cliente['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Tipo de Proyecto -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-briefcase-fill me-1"></i>Proyecto
                            </label>
                            <div class="d-flex flex-wrap gap-2">
                                <!-- Botón: Aplicar Servicio -->
                                <input type="checkbox" class="btn-check" id="toggleServicio" autocomplete="off">
                                <label class="btn btn-outline-success btn-sm d-flex align-items-center gap-1"
                                    for="toggleServicio">
                                    <i class="bi bi-ui-checks-grid"></i> ¿Aplicar servicio?
                                </label>
                            </div>
                        </div>



                        <!-- Proyecto Nuevo -->
                        <div id="proyectoNuevo" class="col-md-6 g-3 my-3 d-none">
                            <div class="row g-3 ">
                                <h6 class="fw-bold text-primary ">
                                    <i class="bi bi-wrench-adjustable-circle-fill me-2"></i>Proyecto
                                </h6>
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre del Proyecto</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control">
                                    <input type="hidden" name="estado" id="nombre" value="pendiente"
                                        class="form-control">
                                </div>

                            </div>

                        </div>
                    </div>

                    <!-- SECCIÓN: Servicios -->
                    <div id="contenedorServicio" class="border-bottom py-3  d-none">
                        <div class="row g-3">
                            <h6 class="fw-bold text-primary ">
                                <i class="bi bi-wrench-adjustable-circle-fill me-2"></i>Servicios
                            </h6>

                            <!-- Servicio -->
                            <div class="col-md-4">
                                <label for="servicio" class="form-label">Servicio <span
                                        class="text-danger">*</span></label>
                                <select name="servicio_id" id="servicio" class="form-select">
                                    <option value="">Seleccione un servicio</option>
                                    <?php foreach ($servicios as $servicio): ?>
                                        <option value="<?= htmlspecialchars($servicio['id']) ?>"
                                            data-precio="<?= htmlspecialchars($servicio['precio_base']) ?>">
                                            <?= htmlspecialchars($servicio['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Mano de Obra -->
                            <div class="col-md-3">
                                <label for="coste_servicio" class="form-label">Costo del Servicio</label>
                                <input type="text" id="coste_servicio" name="coste_servicio" class="form-control"
                                    readonly>
                            </div>

                        </div>
                    </div>

                    <!-- SECCIÓN: Fechas y Estado -->
                    <div class="border-bottom pb-3 mt-4 mb-4">
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-calendar2-week-fill me-2"></i>Fechas y
                            Estado
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="estado_solicitud" class="form-label">Estado de la Solicitud</label>
                                <select name="estado" id="estado_solicitud" class="form-select">
                                    <option value="">Seleccione un estado</option>
                                    <option value="cotizado">Cotizado</option>
                                    <!--  <option value="aprobado">Aprobado</option> -->

                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN: Materiales -->
                    <div class="border-bottom pb-3 mb-4">
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-box2-fill me-2"></i>Materiales</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center" id="tabla-materiales">
                                <thead class="table-light">
                                    <tr>
                                        <th>Material</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Subtotal</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="material_id[]" class="form-select" required>
                                                <option value="">Seleccione</option>
                                                <?php foreach ($materiales as $mat): ?>
                                                    <option value="<?= $mat['material_id'] ?>"
                                                        data-precio="<?= $mat['precio_unitario'] ?>">
                                                        <?= htmlspecialchars($mat['nombre_material']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>

                                        <td><input type="number" name="cantidad[]" class="form-control" step="0.01"
                                                min="0" oninput="calcularSubtotal(this)"></td>
                                        <td><input type="number" name="precio_unitario[]" class="form-control"
                                                step="0.01" min="0" oninput="calcularSubtotal(this)"></td>

                                        <td><input type="text" class="form-control subtotal" readonly></td>
                                        <td><button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="eliminarFila(this)">
                                                <i class="bi bi-trash3-fill"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-outline-primary" onclick="agregarFila()">
                                <i class="bi bi-plus-circle me-1"></i>Agregar Material
                            </button>
                        </div>
                    </div>

                    <!-- SECCIÓN: Detalles -->
                    <div class="border-bottom pb-3 mb-4">
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-journal-text me-2"></i>Detalles
                            adicionales
                        </h6>
                        <div class="col-12">
                            <textarea name="descripcion" class="form-control" rows="3"
                                placeholder="Observaciones, requerimientos especiales, etc."></textarea>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="col-md-6">
                        <label for="mano_obra" class="form-label">Costo de la mano de obra</label>
                        <input type="number" name="mano_obra" id="mano_obra" class="form-control" value="0" min="0"
                            oninput="calcularTotal()">
                    </div>
                    <div class="col-md-6">
                        <label for="total" class="form-label fw-bold">Total del Pedido (XAF)</label>
                        <input type="text" id="total" name="total" class="form-control" readonly>
                    </div>

                    <!-- Botones -->
                    <div class="col-12 d-flex justify-content-between mt-4">
                        <a href="index.php?vista=pedidos" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save2-fill me-1"></i>Guardar Pedido
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>


<script>
    document.querySelectorAll('input[name="opcion"]').forEach(radio => {
        radio.addEventListener('change', () => {
            const value = radio.value;
            document.getElementById('proyectoExistente').classList.toggle('d-none', value !== 'v');
            document.getElementById('proyectoNuevo').classList.toggle('d-none', value !== 'f');
        });
    });

    function calcularSubtotal(el) {
        const row = el.closest('tr');
        const cantidad = parseFloat(row.querySelector('input[name="cantidad[]"]').value) || 0;
        const precio = parseFloat(row.querySelector('input[name="precio_unitario[]"]').value) || 0;
        const subtotal = cantidad * precio;
        row.querySelector('.subtotal').value = subtotal.toFixed(2);
        calcularTotal();
    }

    function calcularTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('total').value = total.toFixed(2);
    }

    function eliminarFila(btn) {
        const row = btn.closest('tr');
        row.remove();
        calcularTotal();
    }

    function agregarFila() {
        const table = document.querySelector('#tabla-materiales tbody');
        const clone = table.rows[0].cloneNode(true);
        clone.querySelectorAll('input').forEach(input => input.value = '');
        table.appendChild(clone);
    }

    function costeServicio() {
        document.getElementById('servicio').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const precio = selectedOption.getAttribute('data-precio');
            document.getElementById('coste_servicio').value = precio ? `${precio} XAF` : '';
        });

    }
    costeServicio();

    // renderizamos directamente el precio del material 
    document.querySelectorAll('#tabla-materiales tbody').forEach(tbody => {
        tbody.addEventListener('change', e => {
            if (e.target && e.target.matches('select[name="material_id[]"]')) {
                const select = e.target;
                const precio = select.selectedOptions[0].dataset.precio;
                const row = select.closest('tr');
                const precioInput = row.querySelector('input[name="precio_unitario[]"]');
                if (precioInput) {
                    precioInput.value = parseFloat(precio || 0).toFixed(2);
                    calcularSubtotal(precioInput);
                }
            }
        });
    });

    // Actualiza el precio unitario al seleccionar un material
    document.querySelectorAll('#tabla-materiales select[name="material_id[]"]').forEach(select => {
        select.addEventListener('change', function () {
            const precio = this.selectedOptions[0].getAttribute('data-precio');
            const row = this.closest('tr');
            if (precio) {
                row.querySelector('input[name="precio_unitario[]"]').value = parseFloat(precio).toFixed(2);
            } else {
                row.querySelector('input[name="precio_unitario[]"]').value = '';
            }
            calcularSubtotalFila(row);
        });
    });

    // Vuelve a conectar eventos cuando se agregue una nueva fila
    window.agregarFila = function () {
        const tabla = document.querySelector('#tabla-materiales tbody');
        const nuevaFila = tabla.rows[0].cloneNode(true);

        nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
        nuevaFila.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        tabla.appendChild(nuevaFila);
        conectarEventosFila(nuevaFila);
    }

    window.eliminarFila = function (btn) {
        const fila = btn.closest('tr');
        const tabla = fila.parentNode;
        if (tabla.rows.length > 1) {
            fila.remove();
            calcularTotal();
        }
    }

    function conectarEventosFila(fila) {
        fila.querySelector('select[name="material_id[]"]').addEventListener('change', function () {
            const precio = this.selectedOptions[0].getAttribute('data-precio');
            const row = this.closest('tr');
            if (precio) {
                row.querySelector('input[name="precio_unitario[]"]').value = parseFloat(precio).toFixed(2);
            } else {
                row.querySelector('input[name="precio_unitario[]"]').value = '';
            }
            calcularSubtotalFila(row);
        });

        fila.querySelectorAll('input[name="cantidad[]"], input[name="precio_unitario[]"]').forEach(input => {
            input.addEventListener('input', () => calcularSubtotalFila(fila));
        });
    }

    window.calcularSubtotal = function (input) {
        const fila = input.closest('tr');
        calcularSubtotalFila(fila);
    }

    function calcularSubtotalFila(fila) {
        const cantidad = parseFloat(fila.querySelector('input[name="cantidad[]"]').value) || 0;
        const precio = parseFloat(fila.querySelector('input[name="precio_unitario[]"]').value) || 0;
        const subtotal = cantidad * precio;
        fila.querySelector('.subtotal').value = subtotal.toFixed(2);
        calcularTotal();
    }

    function calcularTotal() {
        let total = 0;

        document.querySelectorAll('.subtotal').forEach(input => {
            total += parseFloat(input.value) || 0;
        });

        // Sumar el costo del servicio si está presente
        const servicio = document.getElementById('coste_servicio');
        // Sumar el costo del servicio
        if (servicio && servicio.value) {
            const valorServicio = parseFloat(servicio.value.replace(/[^\d.]/g, '')) || 0;
            total += valorServicio;
        }

        // Sumar el costo del personal 
        const personal = document.getElementById('mano_obra');
        if (personal && personal.value) {
            const valorPersonal = parseFloat(personal.value.replace(/[^\d.]/g, '')) || 0;
            total += valorPersonal;
        }


        document.getElementById('total').value = total.toFixed(2) + ' XAF';
    }

    // Detecta cambio en servicio para recalcular total
    const selectServicio = document.getElementById('servicio');
    if (selectServicio) {
        selectServicio.addEventListener('change', function () {
            const selected = this.selectedOptions[0];
            const precio = selected.getAttribute('data-precio');
            const campo = document.getElementById('coste_servicio');
            if (precio) {
                campo.value = `${parseFloat(precio).toFixed(2)} XAF`;
            } else {
                campo.value = '';
            }
            calcularTotal();
        });
    }
    document.getElementById('mano_obra')?.addEventListener('input', calcularTotal);

    // Conecta eventos en filas existentes
    document.querySelectorAll('#tabla-materiales tbody tr').forEach(conectarEventosFila);

    //funcion para validar si se aplica el servicio o no 
    aplicarServicio();
    function aplicarServicio() {
        const toggle = document.getElementById('toggleServicio');
        const contenedor = document.getElementById('contenedorServicio');
        const servicioSelect = document.getElementById('servicio');
        const costeServicioInput = document.getElementById('coste_servicio');

        toggle.addEventListener('change', () => {
            if (toggle.checked) {
                contenedor.classList.remove('d-none');
                servicioSelect.setAttribute('required', 'required');
            } else {
                contenedor.classList.add('d-none');
                servicioSelect.removeAttribute('required');
                servicioSelect.value = '';
                costeServicioInput.value = '';
            }
        });

        servicioSelect.addEventListener('change', () => {
            const precio = servicioSelect.selectedOptions[0].dataset.precio || 0;
            costeServicioInput.value = parseFloat(precio).toFixed(2);
            calcularTotal()// calcularTotalGlobal(); // si usas total general
        });
    }


    document.getElementById('form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        try {
            const response = await fetch('api/guardar_pedidos.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            if (result.success) {
                alert(result.message);
                window.location.reload(); // si deseas refrescar
                location.href = 'index.php?vista=pedidos';
            } else {
                alert(result.message || "Error desconocido");
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Error al guardar la cotización.");
        }
    });


</script>