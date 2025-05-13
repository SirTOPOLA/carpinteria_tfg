<?php
 
// Obtener datos del proveedor
$stmt = $pdo->prepare("SELECT * FROM proveedores");
$stmt->execute();
$proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

 

 
?>
 
   <div id="content" class="container-fluid py-4">
 
        <h4>Registrar Proveedor</h4> 
        <form method="POST" id="form" class="row g-3">
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
            <div class="d-flex justify-content-between">
                <a href="index.php?vista=proveedores" class="btn btn-secondary"><i class="bi bi-arrow-left"></i>Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i>Guardar Cambios</button>
            </div>
        </form>
    </div>
 
 