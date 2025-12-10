<div class="container-fluid">
    <?php require "../layouts/navbar.php"; ?>

    <div class="row">
        <div class="col-md-2 p-0">
            <?php require "../layouts/sidebar.php"; ?>
        </div>

        <div class="col-md-10 p-4" id="main-content">
            <h3 class="mb-4">Bienvenido, <?= htmlspecialchars($usuario['username']) ?></h3>

            <div class="row">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted">Clientes</h6>
                            <h3 id="countClientes">0</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted">Proyectos activos</h6>
                            <h3 id="countProyectos">0</h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
