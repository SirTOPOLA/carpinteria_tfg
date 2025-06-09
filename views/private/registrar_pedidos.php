<?php



// Obtener lista de empleados
$stmt = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo  FROM empleados ORDER BY id");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$productos_sin_stock = $pdo->query("SELECT * FROM productos WHERE stock = 0")->fetchAll(PDO::FETCH_ASSOC);

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
                            m.stock_minimo,
                            m.unidad_medida 
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
                <div class="border-bottom pb-3 mb-2">
                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-vcard-fill me-2"></i>Información del
                        Cliente y Proyecto</h6>

                    <div class="row pb-2 g-3">
                        <div class="col-md-4">
                            <label for="clientes" class="form-label fw-semibold">
                                <i class="bi bi-person-fill me-1"></i>Cliente <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <select name="responsable_id" id="clientes" class="form-select" required>
                                    <option value="">Seleccione un cliente</option>
                                    <?php foreach ($clientes as $cliente): ?>
                                        <option value="<?= htmlspecialchars($cliente['id']) ?>">
                                            <?= htmlspecialchars($cliente['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalNuevoCliente">
                                    <i class="bi bi-person-plus-fill"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Tipo de Producto/Proyecto -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-box-fill me-1"></i>Tipo de Producto
                            </label>
                            <select name="tipo_producto" id="tipo_producto" class="form-select" required>
                                <option value="">Seleccione una opción</option>
                                <option value="nuevo">Nuevo Proyecto</option>
                                <option value="existente">Producto Existente (sin stock)</option>
                            </select>
                        </div>

                        <!-- Seleccionar Producto Existente si aplica -->
                        <div class="col-md-4 d-none" id="producto_existente_div">
                            <label class="form-label">
                                <i class="bi bi-archive-fill me-1"></i>Producto existente
                            </label>
                            <select name="producto_id" id="producto_existente" class="form-select">
                                <option value="">Seleccione producto</option>
                                <?php foreach ($productos_sin_stock as $prod): ?>
                                    <option value="<?= $prod['id'] ?>"
                                     data-descripcion="<?= $prod['descripcion'] ?>">
                                        <?= htmlspecialchars($prod['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Nombre del Proyecto si es nuevo -->
                        <div class="col-md-3 d-none" id="producto_nuevo_div">
                            <label for="nombre" class="form-label">
                                <i class="bi bi-wrench-adjustable-circle-fill me-2"></i> Nombre del Proyecto
                            </label>
                            <input type="text" name="proyecto" id="proyecto" class="form-control">
                            <input type="hidden" name="estado_id" id="estadoPedido" value="cotizado"
                                class="form-control">
                        </div>

                        <!-- Cantidad de Productos -->
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="bi bi-123 me-1"></i>Cantidad de productos <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="cantidad_producto" id="cantidad_producto" class="form-control"
                                value="1" min="1" required>
                        </div>


                        <!-- Proyecto -->

                        <!-- fecha_entrega -->
                        <div id="" class="col-md-2">
                            <label for="fecha_entrega" class="form-label"> <i class="bi bi-number me-2"></i>
                                (días)</label>
                            <input type="text" name="fecha_entrega" id="fecha_entrega" class="form-control">
                        </div>
                        <!-- Tipo de Proyecto -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-briefcase-fill me-1"></i>Servicio
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
                    </div>

                    <div class="row g-3">

                    </div>
                    <!-- SECCIÓN: Servicios -->
                    <div id="contenedorServicio" class="border-top mt-2 py-3  d-none">
                        <div class="row g-3">
                            <!-- Servicio -->
                            <div class="col-md-5">
                                <label for="servicio" class="form-label"> <i
                                        class="bi bi-wrench-adjustable-circle-fill me-2"></i> Servicio <span
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
                            <div class="col-md-5">
                                <label for="coste_servicio" class="form-label">Costo del Servicio</label>
                                <input type="text" id="coste_servicio" name="coste_servicio" class="form-control"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN: Materiales -->
                <div class="border-bottom pb-3 mb-2">
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
                                                    <?= htmlspecialchars($mat['nombre_material']) ?> (
                                                    <?= htmlspecialchars($mat['unidad_medida']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>

                                    <td><input type="number" name="cantidad[]" class="form-control" step="0.01" min="0"
                                            oninput="calcularSubtotal(this)"></td>
                                    <td><input type="number" name="precio_unitario[]" class="form-control" step="0.01"
                                            min="0" oninput="calcularSubtotal(this)"></td>

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
                        <textarea name="descripcion" class="form-control" rows="3" id="descripcion"
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


<!-- Modal para registrar nuevo cliente -->
<div class="modal fade" id="modalNuevoCliente" tabindex="-1" aria-labelledby="modalNuevoClienteLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header bg-dark text-white rounded-top-4 py-3">
                <h5 class="modal-title text-white" id="modalNuevoClienteLabel">
                    <i class="bi bi-person-badge fs-4 me-2"></i>Registrar Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>

            </div>

            <div class="modal-body">
                <form id="formRegistarCliente" method="POST" class="row g-3 needs-validation" novalidate>

                    <!-- Nombre completo -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre completo <span
                                class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                            <div class="invalid-feedback">El nombre es obligatorio.</div>
                        </div>
                    </div>

                    <!-- Correo -->
                    <div class="col-md-6">
                        <label for="correo" class="form-label">Correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                            <input type="email" name="correo" id="correo" class="form-control">
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                            <input type="text" name="telefono" id="telefono" class="form-control">
                        </div>
                    </div>

                    <!-- DIP -->
                    <div class="col-md-6">
                        <label for="codigo" class="form-label">DIP*</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                            <input type="text" name="codigo" id="codigo" class="form-control">
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="col-md-6">
                        <label for="direccion" class="form-label">Dirección</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                            <textarea name="direccion" id="direccion" class="form-control" rows="1"></textarea>
                        </div>
                    </div>

                    <!-- Botones del modal -->
                    <div class="col-12 d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 me-2" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-success rounded-pill px-4">
                            <i class="bi bi-save2-fill me-1"></i>Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>

    document.addEventListener("DOMContentLoaded", function () {
        // ---------- Varios productos --------
        const tipoProducto = document.getElementById("tipo_producto");
        const productoExistenteDiv = document.getElementById("producto_existente_div");
        const productoNuevoDiv = document.getElementById("producto_nuevo_div");
        const cantidadInput = document.getElementById("cantidad_producto");
        const proyecto = document.getElementById("proyecto");

        tipoProducto.addEventListener("change", function () {
            if (this.value === "existente") {
                productoExistenteDiv.classList.remove("d-none");
                productoNuevoDiv.classList.add("d-none");
                //proyecto.setAttribute('disabled', true);
            } else if (this.value === "nuevo") {
                productoNuevoDiv.classList.remove("d-none");
                productoExistenteDiv.classList.add("d-none");
                //proyecto.setAttribute('disabled', false);
            } else {
                // En caso de que no se seleccione nada
                productoExistenteDiv.classList.add("d-none");
                productoNuevoDiv.classList.add("d-none");
            }
        });

        cantidadInput.addEventListener("input", function () {
            actualizarSubtotalesPorCantidad();
            calcularTotal();
        });



    });

    function calcularSubtotal(elemento) {
        const fila = elemento.closest('tr');
        const cantidadMaterial = parseFloat(fila.querySelector('input[name="cantidad[]"]').value) || 0;
        const precioUnitario = parseFloat(fila.querySelector('input[name="precio_unitario[]"]').value) || 0;
        const cantidadProducto = parseInt(document.getElementById("cantidad_producto").value) || 1;

        const subtotal = (cantidadMaterial * cantidadProducto) * precioUnitario;
        fila.querySelector('.subtotal').value = subtotal.toFixed(2);

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
descripcionProductoExistente()
    function descripcionProductoExistente() {
        document.getElementById('producto_existente').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const descripcion = selectedOption.getAttribute('data-descripcion');
            document.getElementById('descripcion').value = descripcion;
        });

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
            if (result.status) {
                alert(result.message);
                window.location.reload(); // si deseas refrescar
                location.href = 'index.php?vista=pedidos';
            } else {
                alert(result.message || "Error desconocido");
                console.log(reult.message)
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Error al guardar la cotización.");
        }
    });



    /* ------- registro rapido de cliente -------------- */


    const formCliente = document.getElementById('formRegistarCliente');
    const modalCliente = new bootstrap.Modal(document.getElementById('modalNuevoCliente'));
    const selectClientes = document.getElementById('clientes');

    formCliente.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!formCliente.checkValidity()) {
            formCliente.classList.add('was-validated');
            return;
        }

        const formData = new FormData(formCliente);

        try {
            const response = await fetch('guardar_cliente_desde_pedido.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                // Cerrar modal
                modalCliente.hide();

                // Limpiar formulario
                formCliente.reset();
                formCliente.classList.remove('was-validated');

                // Agregar nuevo cliente al select
                const option = document.createElement('option');
                option.value = result.id;
                option.textContent = result.nombre;
                option.selected = true;
                selectClientes.appendChild(option);

                // Mostrar notificación opcional
                alert('Cliente registrado correctamente');
            } else {
                alert('Error: ' + result.message);
            }

        } catch (error) {
            console.error('Error al registrar cliente:', error);
            alert('Hubo un error al registrar el cliente.');
        }
    });



    function actualizarSubtotalesPorCantidad() {
        document.querySelectorAll('#tabla-materiales tbody tr').forEach(fila => {
            calcularSubtotal(fila.querySelector('input[name="cantidad[]"]'));
        });
    }

</script>

<!-- 
<script>
 function calcularSubtotal(el) {
        const row = el.closest('tr');
        const cantidad = parseFloat(row.querySelector('input[name="cantidad[]"]').value) || 0;
        const precio = parseFloat(row.querySelector('input[name="precio_unitario[]"]').value) || 0;
        const subtotal = cantidad * precio;
        row.querySelector('.subtotal').value = subtotal.toFixed(2);
        calcularTotal();
    }

function actualizarSubtotalesPorCantidad() {
    document.querySelectorAll('#tabla-materiales tbody tr').forEach(fila => {
        calcularSubtotal(fila.querySelector('input[name="cantidad[]"]'));
    });
}

function calcularTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(input => {
        total += parseFloat(input.value) || 0;
    });

    const manoObra = parseFloat(document.getElementById("mano_obra").value) || 0;
    const costeServicio = parseFloat(document.getElementById("coste_servicio")?.value || 0);

    total += manoObra + costeServicio;

    document.getElementById("total").value = total.toFixed(2);
}
</script>
 -->