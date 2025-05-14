<?php
 

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php?vista=materiales");
    exit;
}

// Obtener material
$stmt = $pdo->prepare("SELECT * FROM materiales WHERE id = ?");
$stmt->execute([$id]);
$material = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$material) {
    header("Location: index.php?vista=materiales");
    exit;
}
?>


<div id="content" class="container container-fluid-ms  py-4">
    <div class="card border-0 shadow rounded-4 col-lg-9 mx-auto">
        <div class="card-header bg-warning text-dark rounded-top-4 py-3">
            <h5 class="mb-0 text-white">
                <i class="bi bi-box-seam-fill fs-4 me-2"></i>
                Editar Material
            </h5>
        </div>

        <div class="card-body px-4 py-4">
            <form id="formEditarMaterial" method="POST" class="row g-4 needs-validation" novalidate>
            <input type="hidden" name="material_id" id="material_id" value="<?= $material['id'] ?>">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">
                        <i class="bi bi-tag-fill me-1 text-primary"></i> Nombre <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required
                    value="<?= htmlspecialchars($material['nombre']) ?>">
                </div>

                <div class="col-md-6">
                    <label for="unidad_medida" class="form-label">
                        <i class="bi bi-rulers me-1 text-primary"></i> Unidad de Medida
                    </label>
                    <input type="text" name="unidad_medida" id="unidad_medida" class="form-control"
                    value="<?= htmlspecialchars($material['unidad_medida']) ?>">
                </div>

                <div class="col-md-6">
                    <label for="stock_minimo" class="form-label">
                        <i class="bi bi-exclamation-triangle-fill me-1 text-warning"></i> Stock Mínimo <span
                            class="text-danger">*</span>
                    </label>
                    <input type="number" name="stock_minimo" id="stock_minimo" class="form-control" min="0" step="1"
                    value="<?= htmlspecialchars($material['stock_minimo']) ?>"  required>
                </div>

                <div class="col-md-6">
                    <label for="descripcion" class="form-label">
                        <i class="bi bi-textarea-t me-1 text-secondary"></i> Descripción
                    </label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3"
                         ><?= htmlspecialchars($material['descripcion']) ?></textarea>
                </div>

                <div class="col-12 d-flex justify-content-between pt-3">
                    <a href="index.php?vista=materiales" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle me-1"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-circle-fill me-1"></i>Actualizar
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
 
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formEditarMaterial');

    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Evita envío tradicional

        const formData = new FormData(form);
 console.log(formData);
        fetch('api/actualizar_materiales.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Mostrar éxito y redirigir o limpiar
                alert(data.message);
                window.location.href = 'index.php?vista=materiales';
            } else {
                alert(data.message);
            }
        })
        .catch(err => {
            console.error('Error en la petición:', err);
            alert('Error al enviar el formulario. Intenta de nuevo.');
        });
    });
});
</script>
 