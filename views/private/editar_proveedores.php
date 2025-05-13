<?php
 $proveedor_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

 if ($proveedor_id <= 0) {
     header("Location: index.php?vista=proveedores");
     exit;
 }

$sql = "SELECT * FROM proveedores WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$proveedor_id]);
$proveedores = $stmt->fetch(PDO::FETCH_ASSOC);

?>
 
<div id="content" class="container-fluid py-4">
  
        <h4 class="mb-3">Registrar Proveedor</h4>
        <form id="form" method="POST" novalidate>
            <div class="row">

     
            <div class="col-md-6 mb-3">
                <label for="nombre" class="form-label">Nombre *</label>
                <input type="text" name="nombre" class="form-control" required
                    value="<?= htmlspecialchars($nombre ?? '') ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($correo ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="contacto" class="form-label">contacto</label>
                <input type="text" name="contacto" class="form-control"
                    value="<?= htmlspecialchars($contacto ?? '') ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control"
                    value="<?= htmlspecialchars($telefono ?? '') ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <textarea name="direccion" class="form-control"><?= htmlspecialchars($direccion ?? '') ?></textarea>
            </div>
            </div>
            <div class="d-flex justify-content-between">
                <a href="index.php?vista=proveedores" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Registrar</button>
            </div>
        </form>
  
</div>

 