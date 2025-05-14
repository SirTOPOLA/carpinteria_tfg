<div id="content" class="container-fluid py-4">
    <div class="container container-fliud-ms py-4">
        <div class="card border-0 shadow rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4 py-3">
                <h5 class="mb-0">
                    <i class="bi bi-box-seam-fill me-2"></i>Registrar Producto
                </h5>
            </div>
            <div class="card-body">
                <form id="formProducto" method="POST" action="guardar_producto.php" enctype="multipart/form-data"
                    class="row g-3 needs-validation" novalidate>

                    <!-- Nombre del producto -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre del Producto <span
                                class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-tag-fill"></i></span>
                            <input type="text" name="nombre" id="nombre" class="form-control"
                                placeholder="Ej: Silla de madera" required>
                            <div class="invalid-feedback">El nombre es obligatorio.</div>
                        </div>
                    </div>

                    <!-- Precio Unitario -->
                    <div class="col-md-6">
                        <label for="precio_unitario" class="form-label">Precio Unitario <span
                                class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                            <input type="number" name="precio_unitario" id="precio_unitario" step="0.01"
                                class="form-control" placeholder="Ej: 1250.50" required>
                            <div class="invalid-feedback">Ingrese un precio válido.</div>
                        </div>
                    </div>

                    <!-- Categoría -->
                    <!--   <div class="col-md-6">
                        <label for="categoria_id" class="form-label">Categoría <span class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-collection-fill"></i></span>
                            <select name="categoria_id" id="categoria_id" class="form-select" required>
                                <option value="">Seleccione una categoría</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat['id']) ?>">
                                        <?= htmlspecialchars($cat['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Seleccione una categoría.</div>
                        </div>
                    </div> -->

                    <!-- Stock -->
                    <div class="col-md-6">
                        <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-boxes"></i></span>
                            <input type="number" name="stock" id="stock" class="form-control" placeholder="Ej: 50"
                                required>
                            <div class="invalid-feedback">Ingrese la cantidad de stock.</div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="col-md-12">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="3"
                            placeholder="Descripción del producto..."></textarea>
                    </div>

                    <!-- Imágenes dinámicas -->
                    <div class="col-md-12">
                        <label class="form-label">Imágenes del Producto</label>
                        <div class="row" id="imagenesContainer"></div>

                        <button type="button" class="btn btn-outline-primary mt-2 rounded-pill"
                            onclick="agregarCampoImagen()">
                            <i class="bi bi-image-fill me-1"></i> Agregar Imagen
                        </button>

                        <div class="form-text">Puedes agregar una o varias imágenes (máx. 2MB cada una, formatos JPG,
                            PNG, etc.).</div>
                    </div>


                    <!-- Botones -->
                    <div class="col-12 d-flex justify-content-between mt-3">
                        <a href="productos.php" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left-circle me-1"></i>Cancelar
                        </a>

                        <button type="submit" class="btn btn-outline-success rounded-pill px-4">
                            <i class="bi bi-save-fill me-1"></i>Registrar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
function agregarCampoImagen() {
    const container = document.getElementById('imagenesContainer');

    const div = document.createElement('div');
    div.classList.add('mb-3');

    const uniqueId = `imgPreview${Date.now()}`;

    div.innerHTML = `
        <div class="input-group col-md-6">
            <span class="input-group-text"><i class="bi bi-file-earmark-image"></i></span>
            <input type="file" name="imagenes[]" accept="image/*" class="form-control" onchange="mostrarPreview(this, '${uniqueId}')" required>
            <button type="button" class="btn btn-outline-danger" onclick="eliminarCampoImagen(this)">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>
        <div class="input-group col-md-6">
        <img id="${uniqueId}" src="" alt="Previsualización" class="mt-2 rounded shadow-sm d-none" style="max-height: 150px;">
        </div>
    `;

    container.appendChild(div);
}

function eliminarCampoImagen(btn) {
    btn.closest('.mb-3').remove();
}

function mostrarPreview(input, previewId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);

    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
        preview.classList.add('d-none');
    }
}
</script>

<script>
    document.getElementById('formProducto').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevenir envío tradicional

        // Validación nativa de Bootstrap
        if (!this.checkValidity()) {
            this.classList.add('was-validated');
            return;
        }

        const form = e.target;
        const formData = new FormData(form);

        fetch('api/guardar_productos.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json()) // Esperamos JSON del backend
            .then(data => {
                if (data.success) {
                    alert('Producto registrado correctamente.');
                    window.location.href = 'index.php?vista=productos'; // redirige si es exitoso
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error al guardar:', error);
                alert('Hubo un error al procesar la solicitud.');
            });
    });
</script>