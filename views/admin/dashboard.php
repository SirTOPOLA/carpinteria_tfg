<?php require "views/layouts/header.php"; ?>

<div id="content" class="container-fluid">
    <div class="app-shell d-flex flex-column">

        <!-- NAV SPA (igual que la vista pública) -->
        <main class="container my-4">

            <ul class="nav nav-pills glass p-3 mb-4 gap-2" id="pills-tab" role="tablist">

                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-overview-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-overview" type="button" role="tab" aria-selected="true">
                        <i class="bi bi-speedometer2 me-1"></i>Panel General
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-clientes-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-clientes" type="button" role="tab">
                        <i class="bi bi-people-fill me-1"></i>Clientes
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-proyectos-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-proyectos" type="button" role="tab">
                        <i class="bi bi-kanban-fill me-1"></i>Proyectos
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-materiales-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-materiales" type="button" role="tab">
                        <i class="bi bi-box-seam me-1"></i>Materiales
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-finanzas-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-finanzas" type="button" role="tab">
                        <i class="bi bi-cash-stack me-1"></i>Finanzas
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <a href="<?php echo urlsite; ?>?page=logout" class="nav-link text-danger fw-bold">
                        <i class="bi bi-box-arrow-right me-1"></i>Salir
                    </a>
                </li>

            </ul>

            <!-- CONTENIDO SPA -->
            <div class="tab-content" id="pills-tabContent">

                <!-- PANEL GENERAL -->
                <div class="tab-pane fade show active" id="pills-overview" role="tabpanel">
                    <div class="row g-3">

                        <!-- MÉTRICAS (Principio de Autoridad + Claridad de propósito) -->
                        <div class="col-md-3">
                            <div class="card card-metric glass shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-briefcase-fill display-5 text-primary"></i>
                                    <h5 class="mt-3">Proyectos Activos</h5>
                                    <h3 class="fw-bold text-primary">
                                        <?= $stats['proyectos_activos'] ?? 0 ?>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card card-metric glass shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-people-fill display-5 text-success"></i>
                                    <h5 class="mt-3">Clientes Registrados</h5>
                                    <h3 class="fw-bold text-success">
                                        <?= $stats['clientes_total'] ?? 0 ?>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card card-metric glass shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-box-seam display-5 text-warning"></i>
                                    <h5 class="mt-3">Stock Bajo</h5>
                                    <h3 class="fw-bold text-warning">
                                        <?= $stats['stock_bajo'] ?? 0 ?>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card card-metric glass shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-cash-coin display-5 text-danger"></i>
                                    <h5 class="mt-3">Pagos Pendientes</h5>
                                    <h3 class="fw-bold text-danger">
                                        <?= $stats['pagos_pendientes'] ?? 0 ?>
                                    </h3>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- PANEL DE ACCIONES (Principio de Compromiso) -->
                    <div class="card glass mt-4 shadow-sm">
                        <div class="card-body">
                            <h4 class="fw-bold">Acciones rápidas</h4>
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <a href="<?php echo urlsite; ?>?page=nuevo_proyecto" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Nuevo Proyecto
                                </a>
                                <a href="<?php echo urlsite; ?>?page=nueva_venta" class="btn btn-success">
                                    <i class="bi bi-cart-check me-1"></i>Registrar Venta
                                </a>
                                <a href="<?php echo urlsite; ?>?page=nuevo_cliente" class="btn btn-info">
                                    <i class="bi bi-person-plus me-1"></i>Nuevo Cliente
                                </a>
                                <a href="<?php echo urlsite; ?>?page=nueva_compra" class="btn btn-warning">
                                    <i class="bi bi-bag-plus me-1"></i>Nueva Compra
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- CLIENTES -->
                <div class="tab-pane fade" id="pills-clientes">
                    <div class="card glass p-3">
                        <h4 class="fw-bold"><i class="bi bi-people-fill me-1"></i>Gestión de Clientes</h4>
                        <p class="text-muted">Aquí puedes administrar todos tus clientes.</p>
                    </div>
                </div>

                <!-- PROYECTOS -->
                <div class="tab-pane fade" id="pills-proyectos">
                    <div class="card glass p-3">
                        <h4 class="fw-bold"><i class="bi bi-kanban-fill me-1"></i>Proyectos</h4>
                        <p class="text-muted">Panel especializado para tus proyectos activos.</p>
                    </div>
                </div>

                <!-- MATERIALES -->
                <div class="tab-pane fade" id="pills-materiales">
                    <div class="card glass p-3">
                        <h4 class="fw-bold"><i class="bi bi-box-seam me-1"></i>Inventario de Materiales</h4>
                        <p class="text-muted">Control total de materiales, stock y proveedores.</p>
                    </div>
                </div>

                <!-- FINANZAS -->
                <div class="tab-pane fade" id="pills-finanzas">
                    <div class="card glass p-3">
                        <h4 class="fw-bold"><i class="bi bi-cash-stack me-1"></i>Finanzas</h4>
                        <p class="text-muted">Resumen financiero del negocio, caja y ventas.</p>
                    </div>
                </div>

            </div>

        </main>

    </div>
</div>

<?php require "views/layouts/footer.php"; ?>
