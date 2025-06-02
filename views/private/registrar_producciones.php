<?php



// Obtener lista de empleados
$stmt = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo  FROM empleados ORDER BY id");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de proyectos
$stmt = $pdo->query("SELECT * FROM proyectos ");
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>



<div id="content" class="container-fliud">
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-warning text-dark rounded-top-4 py-3">
            <h5 class="mb-0 text-white">
                <i class="bi bi-hammer fs-4 me-2"></i>
                Registrar Producción
            </h5>
        </div>


        <div class="card-body">
            <form id="form" method="POST" class="row g-2 needs-validation" novalidate>

                <div class="col-md-6 mb-2 ">
                    <label for="fecha_inicio" class="form-label">
                        <i class="bi bi-calendar-event me-1 text-primary"></i> Fecha de inicio <span
                            class="text-danger">*</span>
                    </label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                </div>

                <div class="col-md-6 mb-2 ">
                    <label for="fecha_fin" class="form-label">
                        <i class="bi bi-calendar-check me-1 text-success"></i> Fecha de finalización <span
                            class="text-danger">*</span>
                    </label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                </div>
                
                <div class="col-md-6 mb-2 ">
                    <label for="proyecto_id" class="form-label">
                        <i class="bi bi-diagram-3 me-1 text-warning"></i> Proyecto asociado <span
                            class="text-danger">*</span>
                        </label>
                        <select name="proyecto_id" id="proyecto" class="form-select" required>
                            <option value="">Seleccione un proyecto</option>
                            <?php foreach ($proyectos as $proyecto): ?>
                                <option value="<?= htmlspecialchars($proyecto['id']) ?>">
                                    <?= htmlspecialchars($proyecto['nombre']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-2 ">
                    <label for="empleado" class="form-label">
                        <i class="bi bi-person-badge me-1 text-info"></i> Responsable de producción <span
                        class="text-danger">*</span>
                    </label>
                    <select name="responsable_id" id="empleado" class="form-select" required>
                        <option value="">Seleccione un empleado</option>
                        <?php foreach ($empleados as $empleado): ?>
                            <option value="<?= htmlspecialchars($empleado['id']) ?>">
                                <?= htmlspecialchars($empleado['nombre_completo']) ?>
                            </option>
                            <?php endforeach; ?>
                    </select>
                </div>
                
                
                <input type="hidden" name="estado" id="estado" value="pendiente" class="form-control" required>

                <div class="col-12 d-flex justify-content-between mt-4">
                    <a href="index.php?vista=producciones" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Producción
                    </button>
                </div>

            </form>
            <div id="mensaje"></div>
        </div>
    </div>
</div> 


<script> 

document.getElementById('form').addEventListener('submit', async function (e) {
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
            const res = await fetch('api/guardar_produccion.php', {
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
                        window.location.href = 'index.php?vista=producciones'; // redirige si es exitoso

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