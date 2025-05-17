<?php


// Obtener productos y servicios
$productos = $pdo->query("SELECT id, nombre FROM productos")->fetchAll(PDO::FETCH_ASSOC);
$servicios = $pdo->query("SELECT id, nombre FROM servicios")->fetchAll(PDO::FETCH_ASSOC);
?>


<div id="content" class="container-fluid py-4">
    <div class="container-fluid container-md px-4 px-sm-3 px-md-4">
        <div class="card border-0 shadow rounded-4 col-lg-8 mx-auto">
            <div class="card-header bg-info text-white rounded-top-4 py-3">
                <h5 class="mb-0">
                    <i class="bi bi-cart-check-fill me-2"></i>Registrar Venta
                </h5>
            </div>

            <div class="card-body">
                <form id="formVenta" method="POST" action="guardar_venta.php" class="needs-validation" novalidate>

                    
                    <!-- Método de pago y Total -->
                    <div class="row g-3 mb-3">
                        <!-- Cliente -->
                        <div class="col-md-6 mb-3">
                            <label for="cliente_id" class="form-label fw-semibold">Cliente <span class="text-danger">*</span></label>
                            <select name="cliente_id" id="cliente_id" class="form-select" required>
                                <option value="">Seleccione</option>
                                <?php
                                $clientes = $pdo->query("SELECT id, nombre FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($clientes as $cliente):
                                ?>
                                <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Por favor selecciona un cliente.</div>
                        </div>
                       
                        <div class="col-md-6">
                            <label for="total_venta" class="form-label fw-semibold">Total (XAF):</label>
                            <input type="text" id="total_venta" name="total" class="form-control text-end fw-bold" readonly value="0.00">
                        </div>
                    </div>

                    <hr>

                    <!-- Botones para agregar filas -->
                    <div class="mb-4 d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary flex-fill" onclick="agregarFila('producto')">
                            <i class="bi bi-box-seam me-1"></i>Agregar producto
                        </button>
                        <button type="button" class="btn btn-outline-success flex-fill" onclick="agregarFila('servicio')">
                            <i class="bi bi-tools me-1"></i>Agregar servicio
                        </button>
                    </div>

                    <!-- Tabla detalles -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tabla-detalles">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Item</th>
                                    <th style="width: 100px;">Cantidad</th>
                                    <th style="width: 120px;">Precio</th>
                                    <th style="width: 80px;">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <!-- Botón Guardar -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?vista=ventas" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left-circle me-1"></i>Volver
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-4">
                            <i class="bi bi-save2-fill me-1"></i>Guardar Venta
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const productos = <?= json_encode($productos) ?>;
    const servicios = <?= json_encode($servicios) ?>;

    function agregarFila(tipo) {
        const tbody = document.querySelector("#tabla-detalles tbody");
        const tr = document.createElement("tr");

        let opciones = '';
        const items = tipo === 'producto' ? productos : servicios;
        items.forEach(item => {
            opciones += `<option value="${item.id}">${item.nombre}</option>`;
        });

        tr.innerHTML = `
    <td>
      <input type="hidden" name="tipo[]" value="${tipo}">
      ${tipo}
    </td>
    <td>
      <select name="${tipo}_id[]" class="form-select">${opciones}</select>
    </td>
    <td><input type="number" name="cantidad[]" class="form-control" value="1" min="1" required></td>
    <td><input type="number" step="0.01" name="precio_unitario[]" class="form-control" value="0.00" required></td>
    <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">X</button></td>
  `;
        tbody.appendChild(tr);
    }
</script>

<script>
// Función para calcular el total de todos los ítems
function calcularTotalVenta() {
  let total = 0;

  // Obtiene todas las filas del tbody de detalles
  const filas = document.querySelectorAll('#tabla-detalles tbody tr');

  filas.forEach(fila => {
    const cantidadInput = fila.querySelector('input[name="cantidad[]"]');
    const precioInput = fila.querySelector('input[name="precio_unitario[]"]');

    const cantidad = parseFloat(cantidadInput?.value || 0);
    const precio = parseFloat(precioInput?.value || 0);

    total += cantidad * precio;
  });

  // Actualiza el campo de total
  document.getElementById('total_venta').value = total.toFixed(2);
}

// Detecta cambios automáticos en inputs
document.addEventListener('input', function(e) {
  if (
    e.target.matches('input[name="cantidad[]"]') ||
    e.target.matches('input[name="precio_unitario[]"]')
  ) {
    calcularTotalVenta();
  }
});

// Si agregas una fila dinámicamente, vuelve a calcular
document.addEventListener('DOMContentLoaded', calcularTotalVenta);
</script>
