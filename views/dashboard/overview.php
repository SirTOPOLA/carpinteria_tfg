<div class="row g-4">

    <div class="col-xl-3 col-md-6">
        <div class="card metric-card">
            <div class="card-body">

                <span class="metric-icon text-primary">
                    <i class="bi bi-kanban"></i>
                </span>

                <h6 class="metric-title">
                    Proyectos activos
                </h6>

                <h2 class="metric-value">
                    <?= $stats['proyectos_activos'] ?? 0 ?>
                </h2>

            </div>
        </div>
    </div>


    <div class="col-xl-3 col-md-6">
        <div class="card metric-card">
            <div class="card-body">

                <span class="metric-icon text-success">
                    <i class="bi bi-people"></i>
                </span>

                <h6 class="metric-title">
                    Clientes
                </h6>

                <h2 class="metric-value">
                    <?= $stats['clientes_total'] ?? 0 ?>
                </h2>

            </div>
        </div>
    </div>


    <div class="col-xl-3 col-md-6">
        <div class="card metric-card">
            <div class="card-body">

                <span class="metric-icon text-warning">
                    <i class="bi bi-box-seam"></i>
                </span>

                <h6 class="metric-title">
                    Material bajo stock
                </h6>

                <h2 class="metric-value">
                    <?= $stats['stock_bajo'] ?? 0 ?>
                </h2>

            </div>
        </div>
    </div>


    <div class="col-xl-3 col-md-6">
        <div class="card metric-card">
            <div class="card-body">

                <span class="metric-icon text-danger">
                    <i class="bi bi-cash"></i>
                </span>

                <h6 class="metric-title">
                    Pagos pendientes
                </h6>

                <h2 class="metric-value">
                    <?= $stats['pagos_pendientes'] ?? 0 ?>
                </h2>

            </div>
        </div>
    </div>

</div>


<div class="card mt-4">

    <div class="card-body">

        <h5 class="fw-bold mb-3">
            Acciones rápidas
        </h5>

        <div class="quick-actions">

            <a href="<?= urlsite ?>?page=nuevo_proyecto" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                Nuevo proyecto
            </a>

            <a href="<?= urlsite ?>?page=nuevo_cliente" class="btn btn-success">
                <i class="bi bi-person-plus"></i>
                Cliente
            </a>

            <a href="<?= urlsite ?>?page=nueva_compra" class="btn btn-warning">
                <i class="bi bi-bag-plus"></i>
                Compra
            </a>

            <a href="<?= urlsite ?>?page=nueva_venta" class="btn btn-info">
                <i class="bi bi-cart-check"></i>
                Venta
            </a>

        </div>

    </div>

</div>