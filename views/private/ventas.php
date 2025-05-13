 

<div id="content" class="container-fluid">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white"><i class="bi bi-receipt me-2"></i>Listado de Ventas</h4>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="buscadorVentas" placeholder="Buscar venta...">
            </div>
            <a href="index.php?vista=registrar_ventas" class="btn btn-secondary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Nuevo venta
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle table-custom mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-person-fill me-1"></i>Cliente</th>
                            <th><i class="bi bi-calendar3 me-1"></i>Fecha</th>
                            <th><i class="bi bi-credit-card me-1"></i>Método de Pago</th>
                            <th><i class="bi bi-currency-dollar me-1"></i>Total</th>
                            <th class="text-center"><i class="bi bi-eye me-1"></i>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ventas = $pdo->query("
                            SELECT v.id, c.nombre AS cliente, v.fecha, v.metodo_pago, v.total 
                            FROM ventas v
                            LEFT JOIN clientes c ON v.cliente_id = c.id
                            ORDER BY v.fecha DESC
                        ")->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($ventas as $venta): ?>
                            <tr>
                                <td><?= $venta['id'] ?></td>
                                <td><?= htmlspecialchars($venta['cliente']) ?></td>
                                <td><?= date('d/m/Y', strtotime($venta['fecha'])) ?></td>
                                <td><?= ucfirst($venta['metodo_pago']) ?></td>
                                <td>$<?= number_format($venta['total'], 2) ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary btn-toggle" data-id="<?= $venta['id'] ?>" aria-expanded="false" title="Ver detalles">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="fila-detalles" id="detalles-<?= $venta['id'] ?>" style="display: none;">
                                <td colspan="6">
                                    <div class="text-center my-2" id="spinner-<?= $venta['id'] ?>">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                    </div>
                                    <div id="contenido-detalles-<?= $venta['id'] ?>"></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Script: detalles dinámicos + buscador -->
<script>
document.querySelectorAll('.btn-toggle').forEach(btn => {
    btn.addEventListener('click', async () => {
        const ventaId = btn.dataset.id;
        const fila = document.getElementById('detalles-' + ventaId);
        const contenido = document.getElementById('contenido-detalles-' + ventaId);
        const spinner = document.getElementById('spinner-' + ventaId);
        const expanded = btn.getAttribute('aria-expanded') === 'true';

        btn.setAttribute('aria-expanded', !expanded);

        if (!expanded) {
            fila.style.display = '';
            spinner.style.display = 'block';
            contenido.innerHTML = '';

            const res = await fetch('obtener_detalles_venta.php?id=' + ventaId);
            const html = await res.text();

            spinner.style.display = 'none';
            contenido.innerHTML = html;
        } else {
            fila.style.display = 'none';
        }
    });
});

// Buscador
document.getElementById('buscadorVentas').addEventListener('keyup', function () {
    const filtro = this.value.toLowerCase();
    document.querySelectorAll('table tbody tr').forEach((fila, i, filas) => {
        // Omitir filas de detalles
        if (fila.classList.contains('fila-detalles')) return;

        const texto = fila.textContent.toLowerCase();
        const coincide = texto.includes(filtro);
        fila.style.display = coincide ? '' : 'none';

        // Ocultar también su fila de detalles asociada
        const nextFila = filas[i + 1];
        if (nextFila && nextFila.classList.contains('fila-detalles')) {
            nextFila.style.display = coincide ? 'none' : 'none';
        }
    });
});
</script>
