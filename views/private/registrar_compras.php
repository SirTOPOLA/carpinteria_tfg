<?php
 

try {
    // Obtener proveedores
    $proveedores = $pdo->query("SELECT id, nombre FROM proveedores")->fetchAll(PDO::FETCH_ASSOC);

    // Obtener materiales con su categoría

    // Consulta para obtener los materiales junto con su categoría
    $materiales = $pdo->query("SELECT  *FROM materiales")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "fatal: " . $e->getMessage();
}
?>

 
<div id="content" class="container-fluid py-4">
 
  <div class="row justify-content-center">
    <div class="col-12 col-xl-11">
      <div class="card shadow rounded-4">
        <div class="card-header bg-dark text-white rounded-top-4 d-flex align-items-center">
          <i class="bi bi-truck me-2 fs-4"></i>
          <h5 class="mb-0">Registrar Compra</h5>
        </div>

        <div class="card-body">
          <form id="form" method="POST" onsubmit="return validarFormulario();">

            <div class="row g-3">
              <div class="col-md-6">
                <label for="proveedor_id" class="form-label">
                  <i class="bi bi-person-lines-fill me-1 text-primary"></i> Proveedor <span class="text-danger">*</span>
                </label>
                <select name="proveedor_id" id="proveedor_id" class="form-select" required>
                  <option value="">Seleccione un proveedor</option>
                  <?php foreach ($proveedores as $prov): ?>
                    <option value="<?= $prov['id'] ?>"><?= htmlspecialchars($prov['nombre']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-6">
                <label for="fecha" class="form-label">
                  <i class="bi bi-calendar-date me-1 text-success"></i> Fecha de compra <span class="text-danger">*</span>
                </label>
                <input type="date" name="fecha" class="form-control" required>
              </div>
            </div>

            <hr class="my-4">

            <h5 class="mb-3"><i class="bi bi-box-seam me-1 text-warning"></i> Materiales</h5>

            <div class="table-responsive">
              <table class="table table-bordered align-middle" id="tabla-materiales">
                <thead class="table-light">
                  <tr>
                    <th>Material</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <select name="material_id[]" class="form-select" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($materiales as $mat): ?>
                          <option value="<?= $mat['id'] ?>"><?= htmlspecialchars($mat['nombre']) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td>
                      <input type="number" name="cantidad[]" class="form-control" step="0.01" min="0" required oninput="calcularSubtotal(this)">
                    </td>
                    <td>
                      <input type="number" name="precio_unitario[]" class="form-control" step="0.01" min="0" required oninput="calcularSubtotal(this)">
                    </td>
                    <td>
                      <input type="text" class="form-control subtotal" readonly>
                    </td>
                    <td>
                      <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">
                        <i class="bi bi-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <button type="button" class="btn btn-primary mb-3" onclick="agregarFila()">
              <i class="bi bi-plus-lg"></i> Agregar material
            </button>

            <div class="mb-3">
              <label for="total" class="form-label">
                <i class="bi bi-cash-coin me-1 text-success"></i> Total
              </label>
              <input type="text" id="total" name="total" class="form-control" readonly>
            </div>

            <div class="d-flex justify-content-between mt-4">
              <a href="index.php?vista=compras" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
              </a>
              <button type="submit" class="btn btn-success">
                <i class="bi bi-save2"></i> Registrar Compra
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
    function agregarFila() {
        const tabla = document.getElementById('tabla-materiales').getElementsByTagName('tbody')[0];
        const nuevaFila = tabla.rows[0].cloneNode(true);
        nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
        tabla.appendChild(nuevaFila);
    }

    function eliminarFila(boton) {
        const fila = boton.closest('tr');
        const tabla = document.getElementById('tabla-materiales').getElementsByTagName('tbody')[0];
        if (tabla.rows.length > 1) {
            fila.remove();
            actualizarTotal();
        }
    }

    function calcularSubtotal(input) {
        const fila = input.closest('tr');
        const cantidad = parseFloat(fila.querySelector('[name="cantidad[]"]').value) || 0;
        const precio = parseFloat(fila.querySelector('[name="precio_unitario[]"]').value) || 0;
        const subtotal = (cantidad * precio).toFixed(2);
        fila.querySelector('.subtotal').value = subtotal;
        actualizarTotal();
    }

    function actualizarTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('total').value = total.toFixed(2);
    }

    function validarFormulario() {
        const materiales = document.querySelectorAll('[name="material_id[]"]');
        for (let i = 0; i < materiales.length; i++) {
            if (materiales[i].value === "") {
                alert("Seleccione todos los materiales.");
                return false;
            }
        }
        return true;
    }
</script>