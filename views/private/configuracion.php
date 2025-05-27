<div id="content" class="container-fluid">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white"><i class="bi bi-gear-fill me-2"></i>Configuración del Sistema</h4>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="buscadorConfig" placeholder="Buscar configuración...">
            </div>
            <a href="index.php?vista=editar_configuracion" class="btn btn-secondary shadow-sm">
                <i class="bi bi-pencil-square me-1"></i> Editar configuración
            </a>
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
</script>
