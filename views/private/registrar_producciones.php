<?php



// Obtener lista de empleados
$stmt = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo  FROM empleados ORDER BY id");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de proyectos
$stmt = $pdo->query("SELECT * FROM proyectos ");
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>


<div id="content" class="container py-4">

    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-9">
            <div class="card shadow rounded-4">
                <div class="card-header bg-dark text-white rounded-top-4 d-flex align-items-center">
                    <i class="bi bi-gear-wide-connected me-2 fs-4"></i>
                    <h5 class="mb-0">Registrar Producción</h5>
                </div>

                <div class="card-body">
                    <form id="form" method="POST" class="row g-2 needs-validation" novalidate>

                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label">
                                <i class="bi bi-calendar-event me-1 text-primary"></i> Fecha de inicio <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="fecha_fin" class="form-label">
                                <i class="bi bi-calendar-check me-1 text-success"></i> Fecha de finalización <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                        </div>

                        <div class="col-md-6">
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

                        <div class="col-md-6">
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

                        <div class="col-md-6">
                            <label for="estado_produccion" class="form-label">
                                <i class="bi bi-hourglass-split me-1 text-secondary"></i> Estado de la producción
                            </label>
                            <select name="estado_produccion" id="estado_produccion" class="form-select">
                                <option value="">Sin estado definido</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="en proceso">En proceso</option>
                                <option value="completado">Completado</option>
                            </select>
                        </div>

                        <div class="col-12 d-flex justify-content-between mt-4">
                            <a href="index.php?vista=producciones" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Producción
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>