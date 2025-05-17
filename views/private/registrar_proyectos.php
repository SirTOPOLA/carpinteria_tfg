

 
<div id="content" class="container-fluid py-4">
 
  <div class="row justify-content-center">
    <div class="col-12 col-xl-11">
      <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
          <i class="bi bi-clipboard2-plus me-2 fs-4"></i>
          <h5 class="mb-0">Registrar Proyecto</h5>
        </div>

        <div class="card-body">
          <form id="formulario" method="POST" class="needs-validation" novalidate>

            <div class="row g-3">
              <div class="col-md-6">
                <label for="nombre" class="form-label">
                  <i class="bi bi-pencil-square me-1 text-primary"></i> Nombre del Proyecto
                  <span class="text-danger">*</span>
                </label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
              </div>

              <div class="col-md-6">
                <label for="estado" class="form-label">
                  <i class="bi bi-bar-chart-steps me-1 text-warning"></i> Estado actual del proyecto
                  <span class="text-danger">*</span>
                </label>
                <select name="estado" id="estado" class="form-select" required>
                  <option value="">Seleccione un estado...</option>
                  <option value="pendiente">Pendiente</option>
                  <option value="en diseño">En diseño</option>
                  <option value="En producción">En producción</option>
                  <option value="Finalizado">Finalizado</option>
                </select>
              </div>

              <div class="col-md-6">
                <label for="fecha_inicio" class="form-label">
                  <i class="bi bi-calendar2-plus me-1 text-success"></i> Fecha de Inicio
                </label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
              </div>

              <div class="col-md-6">
                <label for="fecha_entrega" class="form-label">
                  <i class="bi bi-calendar2-check me-1 text-danger"></i> Fecha de Entrega
                </label>
                <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control">
              </div>

              <div class="col-md-12">
                <label for="descripcion" class="form-label">
                  <i class="bi bi-card-text me-1 text-secondary"></i> Descripción
                </label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="4"></textarea>
              </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
              <a href="index.php?vista=proyectos" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Cancelar
              </a>
              <button type="submit" class="btn btn-success">
                <i class="bi bi-save2"></i> Registrar Proyecto
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

 
<script>
    document.getElementById('form-proyecto').addEventListener('submit', function (e) {
  e.preventDefault();

  const datos = new FormData(this);

  fetch('guardar_proyecto.php', {
    method: 'POST',
    body: datos
  })
  .then(response => response.json())
  .then(data => {
    if (data.exito) {
      alert('Proyecto guardado correctamente.');
      // Opcional: limpiar formulario o redirigir
    } else {
      alert('Error: ' + data.mensaje);
    }
  })
  .catch(error => {
    console.error('Error en la petición:', error);
  });
});

</script>