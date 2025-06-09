<div id="content" class="container-fluid">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white"><i class="bi bi-gear-fill me-2"></i>Configuración del Sistema</h4>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="buscadorConfig" placeholder="Buscar configuración...">
            </div>
            <button class="btn btn-secondary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEditarConfig">
    <i class="bi bi-pencil-square me-1"></i> Editar configuración
</button>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle table-custom mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-building me-1"></i>Empresa</th>
                            <th><i class="bi bi-geo-alt me-1"></i>Dirección</th>
                            <th><i class="bi bi-telephone me-1"></i>Teléfono</th>
                            <th><i class="bi bi-envelope me-1"></i>Correo</th>
                            <th><i class="bi bi-currency-dollar me-1"></i>Moneda</th>
                            <th><i class="bi bi-percent me-1"></i>IVA (%)</th>
                            <th class="text-center"><i class="bi bi-eye me-1"></i>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $config = $pdo->query("SELECT * FROM configuracion LIMIT 1")->fetch(PDO::FETCH_ASSOC);

                        if ($config): ?>
                            <tr>
                                <td><?= $config['id'] ?></td>
                                <td><?= htmlspecialchars($config['nombre_empresa']) ?></td>
                                <td><?= htmlspecialchars($config['direccion']) ?></td>
                                <td><?= htmlspecialchars($config['telefono']) ?></td>
                                <td><?= htmlspecialchars($config['correo']) ?></td>
                                <td><?= htmlspecialchars($config['moneda']) ?></td>
                                <td><?= number_format($config['iva'], 2) ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary btn-toggle" data-id="<?= $config['id'] ?>" aria-expanded="false" title="Ver detalles">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="fila-detalles" id="detalles-<?= $config['id'] ?>" style="display: none;">
                                <td colspan="8">
                                    <div class="row">
                                        <div class="col-md-4 text-center mb-3">
                                            <?php if ($config['logo']): ?>
                                                <img src="api/<?= htmlspecialchars($config['logo']) ?>" alt="Logo" class="img-fluid rounded shadow-sm" style="max-height: 150px;">
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-8">
                                            <p><strong>Misión:</strong> <?= nl2br(htmlspecialchars($config['mision'])) ?></p>
                                            <p><strong>Visión:</strong> <?= nl2br(htmlspecialchars($config['vision'])) ?></p>
                                            <p><strong>Historia:</strong> <?= nl2br(htmlspecialchars($config['historia'])) ?></p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center text-muted">No hay configuraciones registradas aún.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal Editar Configuración -->
<div class="modal fade" id="modalEditarConfig" tabindex="-1" aria-labelledby="modalEditarConfigLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="guardar_configuracion.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalEditarConfigLabel"><i class="bi bi-pencil-square me-2"></i>Editar Configuración</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="<?= $config['id'] ?>">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre de la Empresa</label>
              <input type="text" class="form-control" name="nombre_empresa" value="<?= htmlspecialchars($config['nombre_empresa']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Dirección</label>
              <input type="text" class="form-control" name="direccion" value="<?= htmlspecialchars($config['direccion']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono</label>
              <input type="text" class="form-control" name="telefono" value="<?= htmlspecialchars($config['telefono']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Correo</label>
              <input type="email" class="form-control" name="correo" value="<?= htmlspecialchars($config['correo']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Moneda</label>
              <input type="text" class="form-control" name="moneda" value="<?= htmlspecialchars($config['moneda']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">IVA (%)</label>
              <input type="number" step="0.01" class="form-control" name="iva" value="<?= htmlspecialchars($config['iva']) ?>" required>
            </div>
            <div class="col-md-12">
              <label class="form-label">Misión</label>
              <textarea class="form-control" name="mision" rows="2"><?= htmlspecialchars($config['mision']) ?></textarea>
            </div>
            <div class="col-md-12">
              <label class="form-label">Visión</label>
              <textarea class="form-control" name="vision" rows="2"><?= htmlspecialchars($config['vision']) ?></textarea>
            </div>
            <div class="col-md-12">
              <label class="form-label">Historia</label>
              <textarea class="form-control" name="historia" rows="3"><?= htmlspecialchars($config['historia']) ?></textarea>
            </div>
            <div class="col-md-12">
              <label class="form-label">Logo (opcional)</label>
              <input type="file" class="form-control" name="logo">
              <?php if ($config['logo']): ?>
                <img src="api/<?= htmlspecialchars($config['logo']) ?>" alt="Logo actual" class="img-thumbnail mt-2" style="max-height: 100px;">
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>



<script>
document.querySelectorAll('.btn-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        const fila = document.getElementById('detalles-' + id);
        const expanded = btn.getAttribute('aria-expanded') === 'true';

        fila.style.display = expanded ? 'none' : '';
        btn.setAttribute('aria-expanded', !expanded);
        btn.querySelector('i').classList.toggle('bi-chevron-down');
        btn.querySelector('i').classList.toggle('bi-chevron-up');
    });
});
 
document.querySelector('#modalEditarConfig form').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Guardando...';

    fetch('api/actualizar_configuracion.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'ok') {
            // Mostrar éxito (Bootstrap o SweetAlert2)
            alert('Configuración actualizada correctamente.');

            // Cerrar modal y recargar
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarConfig'));
            modal.hide();

            setTimeout(() => {
                location.reload(); // o puedes actualizar solo la fila con JS si lo prefieres
            }, 500);
        } else {
            alert(data.mensaje || 'Error desconocido.');
        }
    })
    .catch(err => {
        alert('Ocurrió un error al guardar.');
        console.error(err);
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Guardar Cambios';
    });
});
</script>
