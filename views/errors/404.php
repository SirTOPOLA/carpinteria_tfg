<?php require "views/layouts/header.php"; ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="alert alert-danger shadow-sm border-0">

                <div class="d-flex align-items-center">

                    <div class="me-3 fs-1">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>

                    <div class="flex-grow-1">

                        <h5 class="fw-bold mb-1">
                            Error 404 — Página no encontrada
                        </h5>

                        <p class="mb-3 text-muted">
                            La página que intentas acceder no existe o la ruta es incorrecta.
                        </p>

                        <div class="d-flex gap-2">

                            <a href="<?= urlsite ?>?page=dashboard" class="btn btn-sm btn-primary">
                                <i class="bi bi-speedometer2"></i>
                                Ir al Dashboard
                            </a>

                            <a href="<?= urlsite ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-house"></i>
                                Inicio
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php require "views/layouts/footer.php"; ?>