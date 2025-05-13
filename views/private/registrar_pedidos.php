<?php



// Obtener lista de empleados
$stmt = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo  FROM empleados ORDER BY id");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de proyectos
$stmt = $pdo->query("SELECT * FROM proyectos ");
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <div class="card border-0 shadow rounded-4 col-lg-9 mx-auto">
        <div class="card-header bg-warning text-dark rounded-top-4 py-3">
            <h5 class="mb-0 text-white">
         <i class="bi bi-cart-plus me-2"></i>Registrar Pedido 

        </div>

        <div class="card-body px-4 py-4">




            <form id="form" method="POST" class="row g-4 needs-validation" novalidate>
                <!-- Total Pedido -->
                <div class="col-12">
                    <label for="total" class="form-label fw-semibold">Total del Pedido (XAF)</label>
                    <input type="text" id="total" name="total" class="form-control" readonly>
                </div>

                <!-- Cliente -->
                <div class="col-md-6">
                    <label for="clientes" class="form-label">Cliente <span class="text-danger">*</span></label>
                    <select name="responsable_id" id="clientes" class="form-select" required>
                        <option value="">Seleccione un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= htmlspecialchars($cliente['id']) ?>">
                                <?= htmlspecialchars($cliente['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Proyecto -->
                <div class="col-md-6">
                    <label class="form-label d-block">Proyecto</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="opcion" id="optExistente" value="v">
                        <label class="form-check-label" for="optExistente">Existente</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="opcion" id="optNuevo" value="f">
                        <label class="form-check-label" for="optNuevo">Nuevo</label>
                    </div>
                </div>

                <!-- Proyecto Existente -->
                <div id="proyectoExistente" class="col-md-6 d-none">
                    <label for="proyecto" class="form-label">Proyecto existente <span
                            class="text-danger">*</span></label>
                    <select name="proyecto_id" id="proyecto" class="form-select">
                        <option value="">Seleccione un proyecto</option>
                        <?php foreach ($proyectos as $proyecto): ?>
                            <option value="<?= htmlspecialchars($proyecto['id']) ?>">
                                <?= htmlspecialchars($proyecto['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Proyecto Nuevo -->
                <div id="proyectoNuevo" class="row g-3 d-none">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre del Proyecto</label>
                        <input type="text" name="nombre" id="nombre" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="estado" class="form-label">Estado del Proyecto</label>
                        <select name="estado" id="estadoProyecto" class="form-select">
                            <option value="">Seleccione un estado</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="en diseño">En diseño</option>
                            <option value="en producción">En producción</option>
                            <option value="finalizado">Finalizado</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                        <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control">
                    </div>
                </div>

                <!-- Servicios / Mano de Obra -->
                <div class="col-md-6">
                    <label for="servicio" class="form-label">Servicio <span class="text-danger">*</span></label>
                    <select name="servicio_id" id="servicio" class="form-select" required>
                        <option value="">Seleccione un servicio</option>
                        <?php foreach ($servicios as $servicio): ?>
                            <option value="<?= htmlspecialchars($servicio['id']) ?>">
                                <?= htmlspecialchars($servicio['nombre']) ?> -
                                <?= htmlspecialchars($servicio['precio_base']) ?> XAF
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="mano_obra" class="form-label">Costo Mano de Obra</label>
                    <input type="text" id="mano_obra" class="form-control" readonly>
                </div>

                <!-- Fecha y Estado -->
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
                        <option value="aprobado">Aprobado</option>
                        <option value="rechazado">Rechazado</option>
                    </select>
                </div>

                <!-- Tabla de Materiales -->
                <div class="col-12">
                    <h5 class="mt-3"><i class="bi bi-box-seam me-1"></i>Materiales</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tabla-materiales">
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
                                                <option value="<?= $mat['material_id'] ?>">
                                                    <?= htmlspecialchars($mat['nombre_material']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><input type="number" name="cantidad[]" class="form-control" step="0.01" min="0"
                                            oninput="calcularSubtotal(this)"></td>
                                    <td><input type="number" name="precio_unitario[]" class="form-control" step="0.01"
                                            min="0" oninput="calcularSubtotal(this)"></td>
                                    <td><input type="text" class="form-control subtotal" readonly></td>
                                    <td><button type="button" class="btn btn-danger btn-sm"
                                            onclick="eliminarFila(this)"><i class="bi bi-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-outline-primary" onclick="agregarFila()">
                            <i class="bi bi-plus"></i> Agregar Material
                        </button>
                    </div>
                </div>

                <!-- Detalles -->
                <div class="col-12">
                    <label for="descripcion" class="form-label">Detalles adicionales</label>
                    <textarea name="descripcion" class="form-control" rows="3"></textarea>
                </div>

                <!-- Botones de acción -->
                <div class="col-12 d-flex justify-content-between mt-4">
                    <a href="index.php?vista=pedidos" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Pedido
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
</script>