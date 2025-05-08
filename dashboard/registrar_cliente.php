<?php
// dashboard.php principal
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid">

        <h4 class="mb-4">Registrar Nuevo Cliente</h4>

        <!-- FORMULARIO -->
        <form action="../php/guardar_clientes.php" method="POST" novalidate>
            <div class="  col-md-6">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required maxlength="100">
            </div>

            <div class=" col-md-6">
                <label for="correo" class="form-label">Correo electrónico (opcional)</label>
                <input type="email" name="correo" id="correo" class="form-control">
            </div>

            <div class="col-12 col-md-6">
                <label for="codigo" class="form-label">DIP</label>
                <input type="text" name="codigo" id="codigo" class="form-control" maxlength="20">
            </div>

            <!-- <div class="col-12 col-md-6">
                <label for="codigo_acceso" class="form-label">Código de acceso</label>
                <input type="text" name="codigo_acceso" id="codigo_acceso" class="form-control" maxlength="20">
            </div> -->

            <div class="col-12 col-md-6">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" maxlength="20">
            </div>

            <div class="col-12 col-md-6">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" name="direccion" id="direccion" class="form-control" maxlength="20">
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="clientes.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Registrar
                </button>
            </div>
        </form>
    </div>

</main>

<?php include_once("../includes/footer.php"); ?>