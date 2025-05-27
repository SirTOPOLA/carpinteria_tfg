<?php

// Validar ID recibido
$_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($_id <= 0) {
    $_SESSION['alerta'] = "ID proporcionado no válido";
    header("Location: index.php?vista=productos");
    exit;
}

// Validar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    $_SESSION['alerta'] = "Debes iniciar sesión para continuar.";
    header("Location: login.php");
    exit;
}


$stmtProd = $pdo->prepare("
    SELECT p.*, i.ruta_imagen AS imagen
    FROM productos p
    LEFT JOIN imagenes_producto i ON p.id = i.producto_id
    WHERE p.id = :id
");
$stmtProd->execute([':id' => $_id]);
$producto = $stmtProd->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    $_SESSION['alerta'] = "Producto no encontrado.";
    header("Location: index.php?vista=productos");
    exit;
}

// Establecer valores iniciales del formulario
$nombre = $producto['nombre'];
$descripcion = $producto['descripcion'];
$precio = $producto['precio_unitario'];
$stock = $producto['stock'];
$imagen = $producto['imagen'];
?>

<div id="content" class="container-fluid">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card border-0 shadow rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4 py-3">
                <h5 class="mb-0">
                    <i class="bi bi-pencil-square me-2"></i>Editar Producto
                </h5>
            </div>
            <div class="card-body">
                <form id="formProducto" method="POST" action="guardar_producto.php" enctype="multipart/form-data"
                      class="row g-3 needs-validation" novalidate>
                    <!-- Campo oculto -->
                    <input type="hidden" name="producto_id" value="<?= htmlspecialchars($producto['id']) ?>">

                    <!-- Nombre del producto -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-tag-fill"></i></span>
                            <input type="text" name="nombre" id="nombre" class="form-control"
                                   value="<?= htmlspecialchars($nombre) ?>" placeholder="Ej: Silla de madera" required>
                            <div class="invalid-feedback">El nombre es obligatorio.</div>
                        </div>
                    </div>

                    <!-- Precio Unitario -->
                    <div class="col-md-6">
                        <label for="precio_unitario" class="form-label">Precio Unitario <span class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                            <input type="number" name="precio_unitario" id="precio_unitario" step="0.01"
                                   class="form-control" value="<?= htmlspecialchars($precio) ?>" placeholder="Ej: 1250.50"
                                   required>
                            <div class="invalid-feedback">Ingrese un precio válido.</div>
                        </div>
                    </div>

                    <!-- Stock -->
                    <div class="col-md-6">
                        <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-boxes"></i></span>
                            <input type="number" name="stock" id="stock" class="form-control"
                                   value="<?= htmlspecialchars($stock) ?>" placeholder="Ej: 50" required>
                            <div class="invalid-feedback">Ingrese la cantidad de stock.</div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="col-md-12">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="3"
                                  placeholder="Descripción del producto..."><?= htmlspecialchars($descripcion) ?></textarea>
                    </div>

                    <!-- Imágenes -->
                    <div class="col-md-12">
                        <label class="form-label">Imágenes del Producto</label>
                        <div class="row" id="imagenesContainer">
                            <?php if (!empty($imagen) && file_exists("api/" . $imagen)): ?>
                                <div class="mb-2">
                                    <label class="form-label">Imagen actual:</label><br>
                                    <img src="api/<?= htmlspecialchars($imagen) ?>" class="img-thumbnail"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <button type="button" class="btn btn-outline-primary mt-2 rounded-pill"
                                onclick="agregarCampoImagen()">
                            <i class="bi bi-image-fill me-1"></i> Agregar Imagen
                        </button>

                        <div class="form-text">Puedes agregar una o varias imágenes (máx. 2MB cada una, formatos JPG, PNG, etc.).</div>
                    </div>

                    <!-- Botones -->
        <div class="col-12 d-flex justify-content-between mt-3">
          <a href="index.php?vista=productos" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left-circle me-1"></i>Cancelar
          </a>
          <button type="submit" class="btn btn-warning text-dark rounded-pill px-4">
            <i class="bi bi-check-circle-fill me-1"></i>Actualizar
          </button>
        </div>
     
                </form>
                <div id="mensaje" class="mt-3"></div>
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