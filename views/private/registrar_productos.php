<div id="content" class="container-fliud">
    <!-- Card con tabla de roles -->
    <div class="card shadow-sm border-0 mb-4">
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

                  <!-- Imagen única del producto -->
<div class="col-md-12">
    <label for="imagen" class="form-label">Imagen del Producto <span class="text-danger">*</span></label>
    <div class="input-group has-validation">
        <span class="input-group-text"><i class="bi bi-image-fill"></i></span>
        <input type="file" name="imagen" id="imagen" accept="image/*" class="form-control" onchange="mostrarPreviewUnica(this)" required>
    </div>
    <img id="previewUnica" src="" alt="Previsualización" class="mt-3 rounded shadow-sm d-none" style="max-height: 150px;">
    <div class="form-text">Solo se permite una imagen (máx. 2MB, formato JPG/PNG).</div>
</div>



                    <!-- Botones -->
                    <div class="col-12 d-flex justify-content-between mt-3">
                        <a href="index.php?vista=productos" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left-circle me-1"></i>Cancelar
                        </a>

                        <button type="submit" class="btn btn-outline-success rounded-pill px-4">
                            <i class="bi bi-save-fill me-1"></i>Registrar
                        </button>
                    </div>

                </form>
                <div id="mensaje" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<script>
    
    function mostrarPreviewUnica(input) {
    const file = input.files[0];
    const preview = document.getElementById('previewUnica');

    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
        preview.classList.add('d-none');
    }
}

    document.getElementById('formProducto').addEventListener('submit', async function (e) {
        e.preventDefault(); // Prevenir envío tradicional
        let mensaje = document.getElementById('mensaje');
        // Validación nativa de Bootstrap
        if (!this.checkValidity()) {
            this.classList.add('was-validated');
            return;
        }

        const form = e.target;
        const formData = new FormData(form);
        try {
            const res = await fetch('api/guardar_productos_actualizar.php', {
                method: 'POST', body: formData
            })
            const data = await res.json(); // Esperamos JSON del backend
            if (data.success) {
                mensaje.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                setTimeout(() => {
                    mensaje.style.opacity = 0;
                    setTimeout(() => {
                        mensaje.textContent = '';
                        mensaje.style.opacity = 1;
                        window.location.href = 'index.php?vista=productos'; // redirige si es exitoso

                    }, 300); // espera a que se desvanezca
                }, 2000);

            } else {
                mensaje.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                setTimeout(() => {
                    mensaje.textContent = '';
                }, 2000)

            }
        } catch (error) {
            mensaje.innerHTML = `<div class="alert alert-danger">${error}</div>`;
            setTimeout(() => {
                mensaje.textContent = '';
            }, 2000)
        };

    })
</script>