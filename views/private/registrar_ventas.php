<?php


// Obtener productos y servicios
$productos = $pdo->query("SELECT id, nombre FROM productos")->fetchAll(PDO::FETCH_ASSOC);
$servicios = $pdo->query("SELECT id, nombre FROM servicios")->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="container mt-4">
    <h3>Registrar Venta</h3>
    <form id="formVenta" method="POST" action="guardar_venta.php">
        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-select" required>
                <option value="">Seleccione</option>
                <?php
                $clientes = $pdo->query("SELECT id, nombre FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($clientes as $cliente):
                    ?>
                    <option value="<?= $cliente['id'] ?>"><?= $cliente['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="row mb-3">
            <div class="col-12 col-md-6">
                <label for="metodo_pago" class="form-label">Método de pago</label>
                <input type="text" name="metodo_pago" id="metodo_pago" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="total_venta" class="form-label fw-bold">XAF (Total):</label>
                <input type="text" id="total_venta" name="total" class="form-control text-end fw-bold" readonly
                    value="0.00">
            </div>
        </div>

        <hr>

        <div class="mb-3">
            <button type="button" class="btn btn-outline-primary" onclick="agregarFila('producto')">Agregar
                producto</button>
            <button type="button" class="btn btn-outline-success" onclick="agregarFila('servicio')">Agregar
                servicio</button>
        </div>

        <table class="table table-bordered" id="tabla-detalles">
            <thead class="table-light">
                <tr>
                    <th>Tipo</th>
                    <th>Item</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Guardar Venta</button>
        </div>
    </form>
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
