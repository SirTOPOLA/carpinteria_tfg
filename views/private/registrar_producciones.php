<?php


// Obtener lista de empleados
$stmt = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM empleados ORDER BY id");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de pedidos con factura aprobada y saldo pendiente
$stmt = $pdo->prepare("
    SELECT DISTINCT p.id, p.proyecto AS nombre, p.fecha_entrega AS tiempo
    FROM pedidos p
    INNER JOIN estados es ON p.estado_id = es.id
    INNER JOIN clientes c ON p.cliente_id = c.id 
    WHERE es.nombre = 'aprobado'
      AND  p.adelanto != 0
");
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="content" class="container-fluid">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-warning text-white rounded-top-4 py-3">
            <h5 class="mb-0"><i class="bi bi-hammer fs-4 me-2"></i> Registrar Producción</h5>
        </div>

        <div class="card-body">
            <form id="form" method="POST" class="row g-2 needs-validation" novalidate>

                <div class="col-md-6 mb-2">
                    <label for="fecha_inicio" class="form-label">
                        <i class="bi bi-calendar-event me-1 text-primary"></i> Fecha de inicio <span
                            class="text-danger">*</span>
                    </label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                </div>

                <div class="col-md-6 mb-2">
                    <label for="fecha_fin" class="form-label">
                        <i class="bi bi-calendar-check me-1 text-success"></i> Fecha de finalización <span
                            class="text-danger">*</span>
                    </label>
                    <input type="date" id="fecha_fin_visible" class="form-control" readonly disabled>
                    <input type="hidden" name="fecha_fin" id="fecha_fin">

                </div>

                <div class="col-md-6 mb-2">
                    <label for="pedido" class="form-label">
                        <i class="bi bi-diagram-3 me-1 text-warning"></i> Pedido asociado <span
                            class="text-danger">*</span>
                    </label>
                    <select name="pedido_id" id="pedido" class="form-select" required>
                        <option value="">Seleccione un pedido</option>
                        <?php foreach ($pedidos as $pedido): ?>
                            <option value="<?= htmlspecialchars($pedido['id']) ?>"
                                data-tiempo="<?= (int) $pedido['tiempo'] ?>">
                                <?= htmlspecialchars($pedido['nombre']) ?> (<?= (int) $pedido['tiempo'] ?> días)
                            </option>
                        <?php endforeach; ?>
                    </select>

                </div>

                <div class="col-md-6 mb-2">
                    <label for="empleado" class="form-label">
                        <i class="bi bi-person-badge me-1 text-info"></i> Responsable de producción <span
                            class="text-danger">*</span>
                    </label>
                    <select name="responsable_id" id="empleado" class="form-select" required>
                        <option value="">Seleccione un empleado</option>
                        <?php foreach ($empleados as $empleado): ?>
                            <option value="<?= htmlspecialchars($empleado['id']) ?>">
                                <?= htmlspecialchars($empleado['nombre_completo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="hidden" name="estado" id="estado" value="pendiente">

                <div class="col-12 d-flex justify-content-between mt-4">
                    <a href="index.php?vista=producciones" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Producción
                    </button>
                </div>
            </form>
            <div id="mensaje" class="mt-3"></div>
        </div>
    </div>
</div>

<script>
    const fechaInicioInput = document.getElementById('fecha_inicio');
    const fechaFinInput = document.getElementById('fecha_fin');
    const pedidoSelect = document.getElementById('pedido');

    function calcularFechaFin() {
        const fechaInicio = fechaInicioInput.value;
        const dias = parseInt(pedidoSelect.selectedOptions[0]?.dataset.tiempo || 0);

        if (fechaInicio && dias > 0) {
            const fecha = new Date(fechaInicio);
            fecha.setDate(fecha.getDate() + dias);

            const year = fecha.getFullYear();
            const month = String(fecha.getMonth() + 1).padStart(2, '0');
            const day = String(fecha.getDate()).padStart(2, '0');

            fechaFinInput.value = `${year}-${month}-${day}`;
            document.getElementById('fecha_fin_visible').value = fechaFinInput.value;
            console.log( document.getElementById('fecha_fin_visible').value = fechaFinInput.value)
        } else {
            fechaFinInput.value = '';
        }
    }


    fechaInicioInput.addEventListener('change', calcularFechaFin);
    pedidoSelect.addEventListener('change', calcularFechaFin);

    document.getElementById('form').addEventListener('submit', async function (e) {
        e.preventDefault();
        const mensaje = document.getElementById('mensaje');
        const form = e.target;

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const formData = new FormData(form);

        try {
            const res = await fetch('api/guardar_produccion.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                mensaje.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                setTimeout(() => {
                    window.location.href = 'index.php?vista=producciones';
                }, 2000);
            } else {
                mensaje.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        } catch (error) {
            mensaje.innerHTML = `<div class="alert alert-danger">Error de red: ${error}</div>`;
        }
    });
</script>