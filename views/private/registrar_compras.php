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


<div id="content" class="container-fliud">
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-warning text-dark rounded-top-4 py-3">

      <h5 class="mb-0 text-white">
        <i class="bi bi-bag-check-fill me-2 fs-4"></i>
        Registrar Compra
      </h5>
    </div>


    <div class="card-body">
      <form id="form" method="POST" onsubmit="return validarFormulario();">

        <div class="row g-3">
          <!-- PROVEEDOR -->
          <div class="col-md-4">
            <label for="proveedor_id" class="form-label">
              <i class="bi bi-truck me-1 text-primary"></i> Proveedor <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <select name="proveedor_id" id="proveedor_id" class="form-select" required>
                <option value="">Seleccione un proveedor</option>
                <?php foreach ($proveedores as $prov): ?>
                  <option value="<?= $prov['id'] ?>"><?= htmlspecialchars($prov['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
              <button type="button" class="btn btn-outline-primary" title="Agregar proveedor"
                onclick="abrirModalProveedor()">
                <i class="bi bi-person-plus"></i>
              </button>
            </div>
          </div>


          <!-- Campo Fecha corregido con id -->
          <div class="col-md-4">
            <label for="fecha" class="form-label">
              <i class="bi bi-calendar-date me-1 text-success"></i> Fecha de compra <span class="text-danger">*</span>
            </label>
            <input type="date" name="fecha" id="fecha" class="form-control" required>

          </div>
          <!-- Campo codigo corregido con id -->
          <div class="col-md-4">
            <label for="codigo" class="form-label">
              <i class="bi bi-calendar-date me-1 text-success"></i> Código de compra (Opcional) <span
                class="text-danger">*</span>
            </label>
            <input type="text" name="codigo" id="codigo" placeholder="Ej: #206" class="form-control">

          </div>
        </div>

        <hr class="my-4">

        <h5 class="mb-3"><i class="bi bi-layers me-1 text-warning"></i> Materiales</h5>

        <div class="table-responsive">
          <table class="table table-bordered align-middle" id="tabla-materiales">
            <thead class="table-light">
              <tr>
                <th><i class="bi bi-layers me-1 text-primary"></i> Material</th>
                <th><i class="bi bi-123 me-1 text-info"></i> Cantidad</th>
                <th><i class="bi bi-currency-dollar me-1 text-success"></i> Precio Unitario</th>
                <th><i class="bi bi-calculator me-1 text-warning"></i> Subtotal</th>
                <th><i class="bi bi-tools me-1 text-secondary"></i> Acción</th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td>
                  <!-- MATERIAL -->
                  <div class="input-group mt-3">
                    <select name="material_id[]" id="material_id" class="form-select" required>
                      <option value="">Seleccione</option>
                      <?php foreach ($materiales as $mat): ?>
                        <option value="<?= $mat['id'] ?>"><?= htmlspecialchars($mat['nombre']) ?> (<?= htmlspecialchars($mat['unidad_medida']) ?>) </option>
                      <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-outline-primary" onclick="abrirModalMaterial()"
                      title="Nuevo material">
                      <i class="bi bi-plus-circle"></i>
                    </button>
                  </div>

                </td>
                <td>
                  <input type="number" name="cantidad[]" class="form-control" step="0.01" min="0" required
                    oninput="calcularSubtotal(this)">
                </td>
                <td>
                  <input type="number" name="precio_unitario[]" class="form-control" step="0.01" min="0" required
                    oninput="calcularSubtotal(this)">
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
        <div class="col-12 d-flex justify-content-between pt-3">
          <a href="index.php?vista=compras" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle me-1"></i> Cancelar
          </a>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-save-fill me-1"></i> Guardar
          </button>
        </div>


      </form>
    </div>
  </div>
</div>

<!-- Modal Proveedor -->
<div class="modal fade" id="modalProveedor" tabindex="-1" aria-labelledby="modalProveedorLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4">
      <div class="card border-0">
        <div class="card-header bg-warning text-white rounded-top-4 py-3">
          <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Registrar Proveedor</h5>
        </div>
        <div class="card-body">
          <form id="formProveedor" method="POST" class="row g-3 needs-validation" novalidate>
            <div class="col-md-6">
              <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
              <div class="input-group has-validation">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
                <div class="invalid-feedback">El nombre del proveedor es obligatorio.</div>
              </div>
            </div>
            <div class="col-md-6">
              <label for="correo" class="form-label">Correo electrónico (Opcional)</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" name="correo" id="correo" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <label for="contacto" class="form-label">Persona de contacto</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-badge-fill"></i></span>
                <input type="text" name="contacto" id="contacto" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <label for="telefono" class="form-label">Teléfono</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                <input type="text" name="telefono" id="telefono" class="form-control">
              </div>
            </div>
            <div class="col-12">
              <label for="direccion" class="form-label">Dirección</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                <textarea name="direccion" id="direccion" class="form-control" rows="2"></textarea>
              </div>
            </div>

            <div class="col-12 d-flex justify-content-between pt-3">

              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i> Cancelar
              </button>
              <button type="submit" class="btn btn-success">
                <i class="bi bi-save-fill me-1"></i> Guardar
              </button>
            </div>


          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Material -->
<div class="modal fade" id="modalMaterial" tabindex="-1" aria-labelledby="modalMaterialLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4">
      <div class="card border-0">
        <div class="card-header bg-info text-white rounded-top-4 py-3">
          <h5 class="mb-0"><i class="bi  bi-layers me-2"></i>Registrar Material</h5>
        </div>
        <div class="card-body">
          <form id="formMaterial" method="POST" class="row g-3 needs-validation" novalidate>
            <!-- Nombre -->
            <div class="col-md-6">
              <label for="nombre_material" class="form-label">Nombre <span class="text-danger">*</span></label>
              <div class="input-group has-validation">
                <span class="input-group-text"><i class="bi bi-type"></i></span>
                <input type="text" name="nombre" id="nombre_material" class="form-control" required>
                <div class="invalid-feedback">El nombre del material es obligatorio.</div>
              </div>
            </div>

            <!-- Unidad de medida -->
            <div class="col-md-6">
              <label for="unidad_medida" class="form-label">Unidad de medida</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-rulers"></i></span>
                <input type="text" name="unidad_medida" id="unidad_medida" class="form-control"
                  placeholder="Ej. kg, m, unidad">
              </div>
            </div>
 

            <!-- Stock mínimo -->
            <div class="col-md-6">
              <label for="stock_minimo" class="form-label">Stock mínimo</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-exclamation-triangle-fill"></i></span>
                <input type="number" name="stock_minimo" id="stock_minimo" class="form-control" min="0" value="0">
              </div>
            </div>

            <!-- Descripción -->
            <div class="col-12">
              <label for="descripcion" class="form-label">Descripción</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-info-circle-fill"></i></span>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="2"
                  placeholder="Descripción opcional del material"></textarea>
              </div>
            </div>

            <!-- Botones -->
            <div class="col-12 d-flex justify-content-between pt-3">

              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i> Cancelar
              </button>
              <button type="submit" class="btn btn-success">
                <i class="bi bi-save-fill me-1"></i> Guardar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>



<script>
  document.getElementById("form").addEventListener("submit", async function (e) {
    e.preventDefault();
    if (!validarFormulario()) return;

    const form = e.target;
    const formData = new FormData(form);

    try {
      const response = await fetch("api/guardar_compras_actualizar.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert("✅ Compra registrada correctamente.");
        window.location.href = "index.php?vista=compras";
      } else {
        alert("❌ Error al registrar: " + result.message);
      }

    } catch (error) {
      console.error("Error al enviar datos:", error);
      alert("❌ Error inesperado al procesar la solicitud.");
    }
  });

  function agregarFila() {
    const tabla = document.querySelector('#tabla-materiales tbody');
    const nuevaFila = tabla.rows[0].cloneNode(true);
    nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
    tabla.appendChild(nuevaFila);
  }

  function eliminarFila(boton) {
    const fila = boton.closest('tr');
    const tabla = document.querySelector('#tabla-materiales tbody');
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
    const proveedor = document.getElementById("proveedor_id").value;
    const fecha = document.getElementById("fecha").value;
    const materiales = document.querySelectorAll('[name="material_id[]"]');
    const cantidades = document.querySelectorAll('[name="cantidad[]"]');
    const precios = document.querySelectorAll('[name="precio_unitario[]"]');

    if (!proveedor || !fecha) {
      alert("⚠️ Por favor complete todos los campos obligatorios.");
      return false;
    }

    for (let i = 0; i < materiales.length; i++) {
      if (!materiales[i].value || !cantidades[i].value || !precios[i].value) {
        alert("⚠️ Complete todos los campos de los materiales.");
        return false;
      }
    }

    return true;
  }


  function abrirModalProveedor() {
    const modal = new bootstrap.Modal(document.getElementById('modalProveedor'));
    modal.show();
  }

  function abrirModalMaterial() {
    const modal = new bootstrap.Modal(document.getElementById('modalMaterial'));
    modal.show();
  }


  document.getElementById('formProveedor').addEventListener('submit', async function (e) {
    e.preventDefault(); // Evita el envío tradicional

    const form = this;
    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      return;
    }

    const formData = new FormData(form);

    try {
      const response = await fetch('api/guardar_proveedor.php', {
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      if (data.success) {
        agregarProveedorAlSelect(data.proveedor);

      const modal = bootstrap.Modal.getInstance(document.getElementById('modalProveedor'));
      modal.hide();
      form.reset();
      form.classList.remove('was-validated');

      } else {
        alert(data.message || 'Error al registrar proveedor');
      }

    } catch (error) {
      console.error('Error:', error);
      alert('Ocurrió un error inesperado.');
    }
  });



  document.getElementById('formMaterial').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = this;
    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      return;
    }

    const formData = new FormData(form);

    try {
      const response = await fetch('api/guardar_materiales.php', {
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      if (data.success) {
        agregarMaterialAlSelect(data.material);

      const modal = bootstrap.Modal.getInstance(document.getElementById('modalMaterial'));
      modal.hide();
      form.reset();
      form.classList.remove('was-validated');
      } else {
        alert(data.message || 'Error al registrar el material');
      }

    } catch (error) {
      console.error('Error:', error);
      alert('Ocurrió un error inesperado');
    }
  });

  // Se ejecuta después del registro exitoso desde el modal
  function agregarProveedorAlSelect(proveedor) {
    const select = document.getElementById('proveedor_id');
    const option = document.createElement('option');
    option.value = proveedor.id;
    option.textContent = proveedor.nombre;
    option.selected = true;
    select.appendChild(option);
  }

  function agregarMaterialAlSelect(material) {
    const select = document.getElementById('material_id');
    const option = document.createElement('option');
    option.value = material.id;
    option.textContent = material.nombre;
    option.selected = true;
    select.appendChild(option);
  }



</script>