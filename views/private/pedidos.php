<?php


/* if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: pedidos.php");
    exit;
} */

//$id = (int) $_GET['id'];

/* $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0)
    die("ID inválido.");

 */
$sql = "SELECT sp.*,
        c.nombre AS cliente,
        p.nombre AS proyecto    
        FROM solicitudes_proyecto sp
        INNER JOIN clientes c ON sp.cliente_id = c.id
        INNER JOIN proyectos p ON sp.proyecto_id = p.id
         ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetch(PDO::FETCH_ASSOC);

/* $sql = "SELECT sp.*,
        c.nombre AS cliente
        FROM solicitudes_pedidos sp
        INNER JOIN clientes c ON sp.cliente_id = c.id 
         ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$solicitudes_pedidos = $stmt->fetch(PDO::FETCH_ASSOC);

 */

/* if (!$pedidos) {
    header("Location: pedidoss.php");
    exit;
} */
?>


<div id="content" class="container-fluid py-4">

    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-kanban-fill me-2"></i> Gestión de Pedidos
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar pedido..." id="buscador-pedidoss">
            </div>
            <a href="index.php?vista=registrar_pedidos" class="btn btn-secondary">

                <i class="bi bi-plus"> </i>Nuevo pedido</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-card-heading me-1"></i>Proyecto</th>
                            <th><i class="bi bi-file-text me-1"></i>Cliente</th>
                            <th><i class="bi bi-flag-fill me-1"></i>Descripción</th>
                            <th><i class="bi bi-calendar-event me-1"></i>Creado</th>
                            <th><i class="bi bi-calendar-check me-1"></i>Estado</th> 
                            <th><i class="bi bi-clock-history me-1"></i>Coste</th>
                            <th class="text-center"><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                        <a href="index.php?vista=destalles_pedidos&id=<?= $p['id'] ?>"
                                            class="btn btn-sm btn-outline-info" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="index.php?vista=editar_pedidos&id=<?= $p['id'] ?>"
                                            class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

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
    </div>



</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        fetch('api/obtener_datos_pedido.php')
            .then(res => res.json())
            .then(data => {
                cargarClientes(data.clientes);
                cargarMateriales(data.materiales);
                cargarServicios(data.servicios);
                cargarProyectos(data.proyectos);
            });
            console.log
        function cargarClientes(clientes) {
            const select = document.getElementById("clientes");
            clientes.forEach(c => {
                const option = document.createElement("option");
                option.value = c.id;
                option.textContent = c.nombre;
                select.appendChild(option);
            });
        }

        function cargarMateriales(materiales) {
            window.materialesData = materiales; // Para referencia global
            actualizarSelectMateriales();
        }

        function actualizarSelectMateriales() {
            const filas = document.querySelectorAll('#tabla-materiales tbody tr');
            filas.forEach(fila => {
                const select = fila.querySelector('select[name="material_id[]"]');
                select.innerHTML = '<option value="">Seleccione</option>';
                window.materialesData.forEach(mat => {
                    const opt = document.createElement("option");
                    opt.value = mat.id;
                    opt.textContent = `${mat.nombre} - ${mat.precio_unitario} XAF`;
                    opt.dataset.precio = mat.precio_unitario;
                    select.appendChild(opt);
                });
            });
        }

        function cargarServicios(servicios) {
            const select = document.getElementById("servicio");
            servicios.forEach(s => {
                const option = document.createElement("option");
                option.value = s.id;
                option.textContent = `${s.nombre} - ${s.precio_base} XAF`;
                option.dataset.precio = s.precio_base;
                select.appendChild(option);
            });

            select.addEventListener('change', e => {
                const precio = e.target.selectedOptions[0]?.dataset.precio || 0;
                document.getElementById("mano_obra").value = precio;
                actualizarTotal();
            });
        }

        function cargarProyectos(proyectos) {
            const select = document.getElementById("proyecto");
            proyectos.forEach(p => {
                const option = document.createElement("option");
                option.value = p.id;
                option.textContent = p.nombre;
                select.appendChild(option);
            });
        }

        // Eventos en materiales
        document.addEventListener("change", e => {
            if (e.target.matches('select[name="material_id[]"]')) {
                const precio = e.target.selectedOptions[0]?.dataset.precio || 0;
                const fila = e.target.closest("tr");
                fila.querySelector('input[name="precio_unitario[]"]').value = precio;
                calcularSubtotalFila(fila);
                actualizarTotal();
            }
        });

        document.addEventListener("input", e => {
            if (e.target.name === "cantidad[]" || e.target.name === "precio_unitario[]") {
                const fila = e.target.closest("tr");
                calcularSubtotalFila(fila);
                actualizarTotal();
            }
        });

        function calcularSubtotalFila(fila) {
            const cantidad = parseFloat(fila.querySelector('input[name="cantidad[]"]').value) || 0;
            const precio = parseFloat(fila.querySelector('input[name="precio_unitario[]"]').value) || 0;
            fila.querySelector(".subtotal").value = (cantidad * precio).toFixed(2);
        }

        function actualizarTotal() {
            let total = 0;
            document.querySelectorAll(".subtotal").forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            const manoObra = parseFloat(document.getElementById("mano_obra").value) || 0;
            document.getElementById("total").value = (total + manoObra).toFixed(2);
        }
    });









    document.querySelector('#cantidad').addEventListener('input', validarStock);
    document.querySelector('#estado').addEventListener('change', validarStock);
    document.querySelector('#material').addEventListener('change', validarStock);

    function validarStock() {
        const estado = document.querySelector('#estado').value;
        const cantidad = parseInt(document.querySelector('#cantidad').value, 10);
        const selectedMaterialId = parseInt(document.querySelector('#material').value, 10);
        const material = materiales.find(m => m.id === selectedMaterialId);

        if (!material || isNaN(cantidad)) return;

        if (estado === 'aprobado' && cantidad > material.stock_actual) {
            alert(`El stock disponible del material "${material.nombre}" es ${material.stock_actual}. 
                    No puedes aprobar el pedido con una cantidad superior.

                    Puedes:
                    ✅ Cambiar el estado a "pendiente", o
                    🛠️ Ajustar la cantidad a un valor menor.`);
                            }
    }

</script>