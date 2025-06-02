<?php


$errores = [];
$materiales = [];
$producciones = [];
$observaciones = '';

// Obtener materiales y producciones
try {
  $stmt = $pdo->query("SELECT id, nombre, stock_actual, stock_minimo FROM materiales ORDER BY nombre ASC");
  $materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);



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

  // Obtener materiales ya comprados o disponebles en el almacen 

  $sql = 'SELECT 
                m.nombre AS nombre_material,
                dc.cantidad AS cantidad_material
                 FROM detalles_compra dc
                 LEFT JOIN materiales m ON dc.material_id = m.id 
                 ';

  $stmt = $pdo->query($sql);
  $material_comprado = $stmt->fetchAll(PDO::FETCH_ASSOC);
  /* print_r($material_comprado); */

} catch (PDOException $e) {
  $errores[] = "Error al cargar datos: " . $e->getMessage();
}


?>


<div id="content" class="container-fluid py-4">

  <div class="row justify-content-center">
    <div class="col-12 col-xl-11">
      <div class="card shadow rounded-4">
        <div class="card-header bg-dark text-white rounded-top-4 d-flex align-items-center">
          <i class="bi bi-arrow-left-right me-2 fs-4"></i>
          <h5 class="mb-0">Registrar Movimiento</h5>
        </div>

        <div class="card-body">

          <form method="POST" id="form" class="needs-validation" novalidate>

            <!-- Selección de producción -->
            <div class="mb-3">
              <label for="produccion_id" class="form-label">
                <i class="bi bi-hammer text-danger me-1"></i> Producción Asociada
                <span class="text-danger">*</span>
              </label>
              <select name="produccion_id" id="produccion_id" class="form-select" required>
                <option value="">Seleccione una producción</option>
                <?php foreach ($producciones as $prod): ?>
                  <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nombre_proyecto']) ?></option>
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

          <div id="mensaje"></div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', () => {
    const produccionSelect = document.getElementById('produccion_id');
    const btnAgregarMaterial = document.getElementById('btnAgregarMaterial');
    const materialesContainer = document.getElementById('materialesContainer');
    let materialesDisponibles = [];

    produccionSelect.addEventListener('change', async () => {
      materialesContainer.innerHTML = '';
      materialesDisponibles = [];

      const produccionId = produccionSelect.value;
      if (!produccionId) return;

      try {
        const formData = new FormData();
        formData.append('produccion_id', produccionId);

        const response = await fetch('api/obtener_material_movimeinto.php', {
          method: 'POST',
          body: formData
        });

        if (!response.ok) throw new Error('Error al cargar materiales');

        materialesDisponibles = await response.json();

        if (materialesDisponibles.length === 0) {
          materialesContainer.innerHTML = '<div class="alert alert-warning">No hay materiales disponibles para esta producción.</div>';
          btnAgregarMaterial.disabled = true;
          return;
        }

        btnAgregarMaterial.disabled = false;

      } catch (err) {
        console.error(err);
        materialesContainer.innerHTML = '<div class="alert alert-danger">Error al cargar materiales.</div>';
      }
    });

    btnAgregarMaterial.addEventListener('click', () => {
      const usados = obtenerMaterialesUsados();

      // Verificar si ya se agregaron todos los materiales
      if (usados.length >= materialesDisponibles.length) {
        alert('Ya se han agregado todos los materiales disponibles.');
        btnAgregarMaterial.disabled = true;
        return;
      }

      const index = document.querySelectorAll('.material-item').length;

      const div = document.createElement('div');
      div.className = 'material-item border rounded p-3 mb-3 position-relative';
      div.innerHTML = `
       <button type="button" class="btn-close position-absolute top-0 end-0 me-2 mt-2 btn-eliminar-material" aria-label="Eliminar"></button>
      <div class="mb-2">
        <label class="form-label">Material</label>
        <select name="materiales[${index}][material_id]" class="form-select material-select" required>
          <option value="">Seleccione material</option>
          ${materialesDisponibles.map(m => `
            <option value="${m.material_id}" 
                    data-precio="${m.precio_unitario}" 
                    data-max="${m.max_salida}" 
                    data-stock="${m.stock_disponible}">
              ${m.nombre}
            </option>`).join('')}
        </select>
      </div>

      <div class="row">
        <div class="col-md-4 mb-2 d-none">
          <label class="form-label">Precio Unitario</label>
          <input type="text" class="form-control" name="materiales[${index}][precio]" readonly>
        </div>
        <div class="col-md-6 mb-2">
          <label class="form-label">Cantidad</label>
          <input type="number" class="form-control cantidad-input" name="materiales[${index}][cantidad]" min="1" required>
        </div>
        <div class="col-md-6 mb-2">
          <label class="form-label">Máx Permitido</label>
          <input type="text" class="form-control max-input" readonly>
        </div>
      </div>

      <div class="feedback mt-1 text-muted small"></div>
      <div class="text-danger small mensaje-validacion mt-1"></div>
    `;

      materialesContainer.appendChild(div);

      const btnEliminar = div.querySelector('.btn-eliminar-material');
      btnEliminar.addEventListener('click', () => {
        div.remove();
        actualizarOpcionesMateriales();
        verificarLimiteCampos();
      });

      const select = div.querySelector('.material-select');
      const cantidadInput = div.querySelector('.cantidad-input');
      const precioInput = div.querySelector(`[name="materiales[${index}][precio]"]`);
      const maxInput = div.querySelector('.max-input');
      const feedback = div.querySelector('.feedback');
      const msg = div.querySelector('.mensaje-validacion');

      select.addEventListener('change', () => {
        const option = select.options[select.selectedIndex];
        if (!option.value) return;

        const precio = parseFloat(option.dataset.precio);
        const max = parseInt(option.dataset.max);
        const stock = parseInt(option.dataset.stock);
        const tope = Math.min(max, stock);

        precioInput.value = precio.toFixed(2);
        maxInput.value = tope;
        cantidadInput.max = tope;
        cantidadInput.value = '';

        feedback.textContent = `Stock disponible: ${stock}, Máx salida: ${max}`;
        msg.textContent = '';

        actualizarOpcionesMateriales();
        verificarLimiteCampos();
      });

      cantidadInput.addEventListener('input', () => {
        const maxPermitido = parseInt(cantidadInput.max);
        const cantidadIngresada = parseInt(cantidadInput.value);

        if (cantidadIngresada > maxPermitido) {
          msg.textContent = `La cantidad excede el máximo permitido (${maxPermitido}).`;
          cantidadInput.classList.add('is-invalid');
        } else {
          msg.textContent = '';
          cantidadInput.classList.remove('is-invalid');
        }
      });

      actualizarOpcionesMateriales();
      verificarLimiteCampos();
    });

    function obtenerMaterialesUsados() {
      return Array.from(document.querySelectorAll('.material-select'))
        .map(s => s.value)
        .filter(v => v !== '');
    }

    function actualizarOpcionesMateriales() {
      const usados = obtenerMaterialesUsados();

      document.querySelectorAll('.material-select').forEach(select => {
        const actual = select.value;
        Array.from(select.options).forEach(opt => {
          if (!opt.value) return;
          opt.disabled = (opt.value !== actual && usados.includes(opt.value));
        });
      });
    }

    function verificarLimiteCampos() {
      const usados = obtenerMaterialesUsados();
      if (usados.length >= materialesDisponibles.length) {
        btnAgregarMaterial.disabled = true;
      } else {
        btnAgregarMaterial.disabled = false;
      }
    }

    /* envio al backend */
    document.getElementById('form').addEventListener('submit', async e => {
      e.preventDefault();

      const form = e.target;
      const formData = new FormData(form);

      try {
        const response = await fetch('api/guardar_movimientos.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (result.status) {
          alert('Movimiento guardado correctamente');
          form.reset();
          location.href = 'index.php?vista=movimientos'
          // Opcional: limpiar materiales, etc.
        } else {
          alert('Error: ' + result.message);
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Error al guardar el movimiento');
      }
    });


  });

</script>