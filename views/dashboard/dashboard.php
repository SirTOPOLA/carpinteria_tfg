
   
<?php require "views/layouts/header.php"; ?>
<?php require "views/layouts/sidebar.php"; ?>
    
        <div class="container-fluid p-4">
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card p-3 bg-white border-start border-primary border-4">
                        <small class="text-muted fw-bold">PROYECTOS ACTIVOS</small>
                        <h2 class="fw-bold mb-0">24</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 bg-white border-start border-success border-4">
                        <small class="text-muted fw-bold">CAJA DEL DÍA</small>
                        <h2 class="fw-bold mb-0">€1,280.50</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 bg-white border-start border-warning border-4">
                        <small class="text-muted fw-bold">STOCK BAJO</small>
                        <h2 class="fw-bold mb-0">7 Items</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 bg-white border-start border-info border-4">
                        <small class="text-muted fw-bold">PENDIENTE COBRO</small>
                        <h2 class="fw-bold mb-0">€4,120.00</h2>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Control de Proyectos (Selectividad de Activos)</h5>
                    <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">Nuevo Proyecto</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted">
                                <th class="ps-4">CÓDIGO</th>
                                <th>CLIENTE</th>
                                <th>ESTADO</th>
                                <th>TIPO</th>
                                <th>TOTAL ESTIMADO</th>
                                <th class="pe-4 text-end">GESTIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-4 fw-bold text-primary">PRJ-2026-001</td>
                                <td>Diseños Malabo S.L.</td>
                                <td><span class="badge bg-info-subtle text-info px-3">En Proceso</span></td>
                                <td>Mueble Medida</td>
                                <td class="fw-bold">€2,450.00</td>
                                <td class="pe-4 text-end">
                                    <div class="btn-group shadow-sm">
                                        <button class="btn btn-white btn-sm border"><i class="bi bi-eye"></i></button>
                                        <button class="btn btn-white btn-sm border"><i
                                                class="bi bi-pencil"></i></button>
                                        <button class="btn btn-white btn-sm border text-danger"><i
                                                class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
   

  <?php require "views/layouts/footer.php"; ?>