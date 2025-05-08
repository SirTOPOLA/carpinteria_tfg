<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-3">
    <h2>Registrar Material</h2>


    <form method="POST" action="../php/guardar_materiales.php" class="row g-3 needs-validation" novalidate>
        <div class="col-12 col-md-6">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>



        <div class="col-12 col-md-6">
            <label for="unidad_medida" class="form-label">Unidad de Medida:</label>
            <input type="text" name="unidad_medida" id="unidad_medida" class="form-control">
        </div>

       <!--  <div class="col-12 col-md-6">
            <label for="stock_actual" class="form-label">Stock Actual:</label>
            <input type="number" name="stock_actual" id="stock_actual" class="form-control" step="0.01" value="0.00"
                required>
        </div>
 -->
       <!--  <div class="col-12 col-md-6">
            <label for="stock_minimo" class="form-label">Stock Mínimo:</label>
            <input type="number" name="stock_minimo" id="stock_minimo" class="form-control" step="0.01" value="0.00"
                required>
        </div> -->
        <div class="col-12 col-md-6">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3"></textarea>
        </div>
        <div class="col-12 ">
            <button type="submit" class="btn btn-primary">Registrar Material</button>
            <a href="materiales.php" class="btn btn-secondary">Volver</a>

        </div>

    </form>

</main>

<?php include '../includes/footer.php'; ?>