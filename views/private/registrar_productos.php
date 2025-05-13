
   <div id="content" class="container-fluid py-4">
    
            <h4 class="mb-4">Registrar Nuevo Producto</h4>

            

            <form method="POST" novalidate>
                <div class="form-floating mb-3">
                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del producto"
                         required>
                    <label for="nombre">Nombre del producto</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="file" name="imagen" id="imagen" class="form-control" placeholder="imagen del producto"
                        value=" " required>
                    <label for="Imagen">Imagen del producto</label>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción (opcional)</label>
                    <textarea name="descripcion" id="descripcion" class="form-control"
                        rows="3"> </textarea>
                </div>

               
                <div class="form-floating mb-4">
                    <input type="number" step="0.01" name="precio" id="precio" class="form-control" placeholder="Precio"
                        required>
                    <label for="precio">Precio</label>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?vista=productos" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
 
 
 