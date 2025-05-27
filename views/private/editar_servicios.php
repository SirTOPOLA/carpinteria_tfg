<?php


$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = ?");
$stmt->execute([$id]);
$servicio = $stmt->fetch();

if (!$servicio) {
  echo "Servicio no encontrado.";
  exit;
}
?>

<div id="content" class="container-fliud">
  <div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-warning text-dark rounded-top-4 py-3">
      <h5 class="mb-0 text-white">
      <i class="bi bi-pencil-square me-2"></i>
        Editar Servicio
      </h5>
    </div>

    <div class="card-body px-4 py-4">
      <form id="formEditarServicio" method="POST" class="row g-4 needs-validation" novalidate>
        <input type="hidden" name="id" id="id" value="<?= htmlspecialchars($servicio['id']) ?>">

        <div class="col-md-12">
          <label for="nombre" class="form-label">
            <i class="bi bi-card-text me-1 text-primary"></i> Nombre del Servicio <span class="text-danger">*</span>
          </label>
          <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej. Corte, Pintura, Reparación"
            value="<?= htmlspecialchars($servicio['nombre']) ?>" required>
          <div class="invalid-feedback">
            Por favor, ingresa un nombre válido.
          </div>
        </div>

        <div class="col-md-12">
          <label for="descripcion" class="form-label">
            <i class="bi bi-textarea-resize me-1 text-secondary"></i> Descripción
          </label>
          <textarea name="descripcion" id="descripcion" class="form-control" rows="3"
            placeholder="Describe brevemente el servicio..."><?= htmlspecialchars($servicio['descripcion']) ?></textarea>
        </div>

        <div class="col-md-6">
          <label for="precio_base" class="form-label">
            <i class="bi bi-currency-dollar me-1 text-success"></i> Precio Base <span class="text-danger">*</span>
          </label>
          <input type="number" name="precio_base" id="precio_base" class="form-control" step="0.01" min="0"
            placeholder="Ej. 50.00" value="<?= htmlspecialchars($servicio['precio_base']) ?>" required>
          <div class="invalid-feedback">
            Por favor, ingresa un precio válido mayor o igual a 0.
          </div>
        </div>

        <div class="col-md-6">
          <label for="unidad" class="form-label">
            <i class="bi bi-rulers me-1 text-primary"></i> Unidad <span class="text-danger">*</span>
          </label>
          <input type="text" name="unidad" id="unidad" class="form-control" required
            placeholder="Ej. por hora, por unidad" value="<?= htmlspecialchars($servicio['unidad']) ?>">
          <div class="invalid-feedback">
            Por favor, ingresa una unidad válida.
          </div>
        </div>
        <div class="col-md-12">
          <a href="#" id="toggleActivo" class="btn btn-sm  toggle-estado"
            data-estado="<?= htmlspecialchars($servicio['activo']) ?>">

            <?php if (htmlspecialchars($servicio['activo']) == '1'): ?>
              <span class="btn btn-success"> <i class="bi bi-toggle-on me-1"></i> Servicio Activado</span>
            <?php else: ?>
              <span class="btn btn-danger"> <i class="bi bi-toggle-on me-1"></i> Servicio Desactivado</span>
            <?php endif; ?>
          </a>
          <input class="form-check-input" type="hidden" name="activo" id="activo">
        </div>

        <!-- Botones -->
        <div class="col-12 d-flex justify-content-between mt-3">
          <a href="index.php?vista=servicios" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left-circle me-1"></i>Cancelar
          </a>
          <button type="submit" class="btn btn-warning text-dark rounded-pill px-4">
            <i class="bi bi-check-circle-fill me-1"></i>Actualizar
          </button>
        </div>

        
      </form>

      <div id="mensaje" class="mt-3"></div>
    </div>
  </div>
</div>


<script>

  document.getElementById('formEditarServicio').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    try {
      const res = await fetch('api/actualizar_servicios.php', {
        method: 'POST',
        body: formData
      });

      const data = await res.json();
      const mensaje = document.getElementById('mensaje');

      if (data.success) {
        mensaje.innerHTML = `<div class="alert alert-success">${data.message}</div>`;

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

      } else {
        mensaje.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        setTimeout(() => {
          mensaje.textContent = '';
        }, 2000)
      }
    } catch (error) {
      mensaje.innerHTML = `<div class="alert alert-danger">Error al enviar los datos.</div>`;
      console.error(error);
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