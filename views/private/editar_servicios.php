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

<div id="content" class="container container-fluid-ms py-4">
  <div class="card border-0 shadow rounded-4 col-lg-9 mx-auto">
    <div class="card-header bg-warning text-dark rounded-top-4 py-3">
      <h5 class="mb-0 text-white">
        <i class="bi bi-gear-wide-connected fs-4 me-2"></i>
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
          <input type="text" name="nombre" id="nombre" class="form-control" 
                 placeholder="Ej. Corte, Pintura, Reparación"
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
                 placeholder="Ej. 50.00"
                 value="<?= htmlspecialchars($servicio['precio_base']) ?>" required>
          <div class="invalid-feedback">
            Por favor, ingresa un precio válido mayor o igual a 0.
          </div>
        </div>

        <div class="col-md-6">
          <label for="unidad" class="form-label">
            <i class="bi bi-rulers me-1 text-primary"></i> Unidad <span class="text-danger">*</span>
          </label>
          <input type="text" name="unidad" id="unidad" class="form-control" required
                 placeholder="Ej. por hora, por unidad"
                 value="<?= htmlspecialchars($servicio['unidad']) ?>">
          <div class="invalid-feedback">
            Por favor, ingresa una unidad válida.
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="activo" id="activo" <?= $servicio['activo'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="activo">
              <i class="bi bi-toggle-on me-1 text-success"></i> Servicio Activo
            </label>
          </div>
        </div>

        <div class="col-12 d-flex justify-content-between pt-3">
          <a href="index.php?vista=servicios" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle me-1"></i> Cancelar
          </a>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-save-fill me-1"></i> Actualizar
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

        const data = {
            id: document.getElementById('id').value,
            nombre: document.getElementById('nombre').value,
            descripcion: document.getElementById('descripcion').value,
            precio_base: document.getElementById('precio_base').value,
            unidad: document.getElementById('unidad').value,
            activo: document.getElementById('activo').checked ? 1 : 0
        };

        const res = await fetch('api/actualizar_servicios.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const resultado = await res.json();
        const mensaje = document.getElementById('mensaje');

        if (resultado.success) {
            mensaje.innerHTML = `<div class="alert alert-success">${resultado.message}</div>`;
            window.location.href = 'index.php?vista=servicios';
        } else {
            mensaje.innerHTML = `<div class="alert alert-danger">${resultado.message}</div>`;
        }
    });
</script>