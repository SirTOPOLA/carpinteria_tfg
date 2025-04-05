<?php include_once("../includes/header.php"); ?> 
<div class="d-flex">
    <?php include_once("../includes/sidebar.php"); ?>

    <div class="main p-4">
        <!-- Contenido principal aquí -->
        <!-- Contenido principal -->

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Dashboard del Administrador</h2>
            <div>
                <span class="me-3">Admin</span>
                <a href="#" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Salir</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h5>Ventas Hoy</h5>
                    <h3>$0.00</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h5>Stock Bajo</h5>
                    <h3>0</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h5>Ordenes Pendientes</h5>
                    <h3>0</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h5>Ganancias Mes</h5>
                    <h3>$0.00</h3>
                </div>
            </div>
        </div>

    </div>
</div>
<?php include_once("../includes/footer.php"); ?>