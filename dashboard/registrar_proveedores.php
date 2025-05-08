<?php
require_once("../includes/conexion.php");

 

// Obtener datos del proveedor
$stmt = $pdo->prepare("SELECT * FROM proveedores");
$stmt->execute();
$proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

 

 
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
        <h4>Registrar Proveedor</h4>

        

        <form method="POST" action="../php/guardar_proveedor.php" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control"
                     required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control"  required>
            </div>
            <div class="col-md-6">
                <label class="form-label">contacto</label>
                <input type="text" name="contacto" class="form-control"  required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Correo</label>
                <input type="email" name="correo" class="form-control  ">
            </div>
            <div class="col-md-6">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" >
            </div>
            <div class="col-12 text-end">
                <a href="proveedores.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i>Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i>Guardar Cambios</button>
            </div>
        </form>
    </div>
</main>
<?php include_once("../includes/footer.php"); ?>