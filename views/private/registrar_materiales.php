




<div id="content" class="container-fluid py-4">
    <h2>Registrar Material</h2>


    <form id="formRegistrarMaterial" method="POST"  class="row g-3 needs-validation" novalidate>
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
        <div class="col-12 col-md-6">
            <label for="stock_minimo" class="form-label">Stock Mínimo:</label>
            <input type="number" name="stock_minimo" id="stock_minimo" class="form-control" value="0"
                required>
        </div>
        <div class="col-12 col-md-6">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3"></textarea>
        </div>
        <div class="col-12 d-flex justify-content-between ">
            <a href="index.php?vista=materiales" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Registrar Material</button>

        </div>

    </form>

</div>
 