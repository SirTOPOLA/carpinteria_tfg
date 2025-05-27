<div id="content" class="container-fliud">
  <div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-warning text-dark rounded-top-4 py-3">
      <h5 class="mb-0 text-white">
        <i class="bi bi-plug fs-4 me-2"></i>
        Registrar Servicio
      </h5>
    </div>

    <div class="card-body px-4 py-4">
      <form id="formServicio" method="POST" class="row g-4 needs-validation" novalidate>

        <div class="col-md-12">
          <label for="nombre" class="form-label">
            <i class="bi bi-card-text me-1 text-primary"></i> Nombre del Servicio <span class="text-danger">*</span>
          </label>
          <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej. Corte, Pintura, Reparación"
            required>
        </div>

        <div class="col-md-12">
          <label for="descripcion" class="form-label">
            <i class="bi bi-textarea-resize me-1 text-secondary"></i> Descripción
          </label>
          <textarea name="descripcion" id="descripcion" class="form-control" rows="3"
            placeholder="Describe brevemente el servicio..."></textarea>
        </div>

        <div class="col-md-6">
          <label for="precio_base" class="form-label">
            <i class="bi bi-currency-dollar me-1 text-success"></i> Precio Base <span class="text-danger">*</span>
          </label>
          <input type="number" name="precio_base" id="precio_base" class="form-control" step="0.01" min="0" required
            placeholder="Ej. 50.00">
        </div>

        <div class="col-md-6">
          <label for="unidad" class="form-label">
            <i class="bi bi-rulers me-1 text-primary"></i> Unidad <span class="text-danger">*</span>
          </label>
          <input type="text" name="unidad" id="unidad" class="form-control" required
            placeholder="Ej. por hora, por unidad">
        </div>

        <div class="col-md-12">
          <a href="#" id="toggleActivo" class="btn btn-sm btn-success toggle-estado" data-estado="1">
            <i class="bi bi-toggle-on me-1"></i>
            Servicio Activado
          </a>
          <!-- Input oculto que se enviará con el formulario -->
          <input type="hidden" name="activo" id="activo" value="1">
        </div>

        <div class="col-12 d-flex justify-content-between pt-3">
          <a href="index.php?vista=servicios" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle me-1"></i> Cancelar
          </a>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-save-fill me-1"></i> Guardar
          </button>
        </div>

      </form>

      <div id="mensaje" class="mt-3"></div>
    </div>
  </div>
</div>


<script>
  document.getElementById('formServicio').addEventListener('submit', async function (e) {
    e.preventDefault();
    let mensaje = document.getElementById('mensaje');
    const form = e.target;
    const formData = new FormData(form);

    // Asegurar que el checkbox esté presente (aunque esté desmarcado)
    if (!formData.has('activo')) {
      formData.append('activo', 0); // si no se marcó, enviar como 0
    }

    try {
      const response = await fetch('api/guardar_servicios.php', {
        method: 'POST',
        body: formData
      });
      const data = await response.json();

      if (data.success) {
        mensaje.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        form.reset(); // Limpiar formulario 
        setTimeout(() => {
          mensaje.style.opacity = 0;
          setTimeout(() => {
            mensaje.textContent = '';
            mensaje.style.opacity = 1;
            if (data.success) {
              window.location.href = 'index.php?vista=servicios';
            }
          }, 300); // espera a que se desvanezca
        }, 2000);

      }
    } catch (error) {
      mensaje.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
      setTimeout(() => {
        mensaje.textContent = '';
      }, 2000)
    }
  });


  function alterarEstato() {
    const toggle = document.getElementById('toggleActivo');
    const input = document.getElementById('activo');

    toggle.addEventListener('click', (e) => {
      e.preventDefault();

      const estadoActual = toggle.getAttribute('data-estado');
      const nuevoEstado = estadoActual === '1' ? '0' : '1';

      // Actualiza ícono, clase y texto
      toggle.setAttribute('data-estado', nuevoEstado);
      input.value = nuevoEstado;

      if (nuevoEstado === '1') {
        toggle.classList.remove('btn-danger');
        toggle.classList.add('btn-success');
        toggle.innerHTML = '<i class="bi bi-toggle-on me-1"></i> Servicio Activado';
      } else {
        toggle.classList.remove('btn-success');
        toggle.classList.add('btn-danger');
        toggle.innerHTML = '<i class="bi bi-toggle-off me-1"></i> Servicio Desactivado';
      }
    });
  }

  alterarEstato()
</script>