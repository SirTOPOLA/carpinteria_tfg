
<?php
require_once("../includes/conexion.php");

 
?>

<?php
// dashboard.php principal
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
include '../includes/conexion.php'; // Asegúrate de tener la conexión a base de datos aquí
?>
<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid">
        <div class="col-md-7">
            <h4 class="mb-3">Registrar Proveedor</h4> 
            <form method="POST" novalidate>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" required
                        value="<?= htmlspecialchars($nombre ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" name="correo" class="form-control"
                        value="<?= htmlspecialchars($correo ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="contacto" class="form-label">contacto</label>
                    <input type="text" name="contacto" class="form-control"
                        value="<?= htmlspecialchars($contacto ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                        value="<?= htmlspecialchars($telefono ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <textarea name="direccion" class="form-control"><?= htmlspecialchars($direccion ?? '') ?></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="proveedores.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Registrar</button>
                </div>
            </form>
        </div>
    </div>
    </div>
</main>
<?php include_once("../includes/footer.php"); ?>