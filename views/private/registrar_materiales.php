


<div id="content" class="container container-fluid-ms  py-4">
    <div class="card border-0 shadow rounded-4 col-lg-9 mx-auto">
        <div class="card-header bg-warning text-dark rounded-top-4 py-3">
            <h5 class="mb-0 text-white">
                <i class="bi bi-box-seam-fill fs-4 me-2"></i>
                Registrar Material
            </h5>
        </div>

        <div class="card-body px-4 py-4">
            <form id="formRegistrarMaterial" method="POST" class="row g-4 needs-validation" novalidate>

                <div class="col-md-6">
                    <label for="nombre" class="form-label">
                        <i class="bi bi-tag-fill me-1 text-primary"></i> Nombre <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required
                        placeholder="Ej. Madera Pino">
                </div>

                <div class="col-md-6">
                    <label for="unidad_medida" class="form-label">
                        <i class="bi bi-rulers me-1 text-primary"></i> Unidad de Medida
                    </label>
                    <input type="text" name="unidad_medida" id="unidad_medida" class="form-control"
                        placeholder="Ej. metros, kg, unidades">
                </div>

                <div class="col-md-6">
                    <label for="stock_minimo" class="form-label">
                        <i class="bi bi-exclamation-triangle-fill me-1 text-warning"></i> Stock Mínimo <span
                            class="text-danger">*</span>
                    </label>
                    <input type="number" name="stock_minimo" id="stock_minimo" class="form-control" min="0" step="1"
                        value="0" required>
                </div>

                <div class="col-md-6">
                    <label for="descripcion" class="form-label">
                        <i class="bi bi-textarea-t me-1 text-secondary"></i> Descripción
                    </label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3"
                        placeholder="Ej. Madera de pino de alta resistencia"></textarea>
                </div>

                <div class="col-12 d-flex justify-content-between pt-3">
                    <a href="index.php?vista=materiales" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle me-1"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle-fill me-1"></i> Registrar Material
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
 

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formRegistrarMaterial');

    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Evita envío tradicional

        const formData = new FormData(form);
 
        fetch('api/guardar_materiales.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Mostrar éxito y redirigir o limpiar
                alert('Material registrado correctamente');
                window.location.href = 'index.php?vista=materiales';
            } else {
                alert(data.message || 'Ocurrió un error al procesar el formulario.');
            }
        })
        .catch(err => {
            console.error('Error en la petición:', err);
            alert('Error al enviar el formulario. Intenta de nuevo.');
        });
    });
});
</script>