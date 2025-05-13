<?php
 
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php?vista=producciones");
    exit;
}

// Obtener lista de empleados
$stmt = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo  FROM empleados ORDER BY id");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de proyectos
$stmt = $pdo->query("SELECT * FROM proyectos ");
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

 
<div id="content" class="container-fluid py-4">
    <h4 class="mb-4">Registrar Produccion</h4>

    <form  id="form" method="POST" class="row g-3 needs-validation" novalidate>

        <div class="col-12 col-md-6">
            <label for="fecha_inicio" class="form-label">Nombre de fecha inicio <span
                    class="text-danger">*</span></label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="fecha_fin" class="form-label">Nombre de fecha fin <span class="text-danger">*</span></label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="proyecto_id" class="form-label">proyecto <span class="text-danger">*</span></label>
            <select name="proyecto_id" id="proyecto" class="form-select" required>
                <option value="">Seleccione un proyecto</option>
                <?php foreach ($proyectos as $proyecto): ?>
                    <option value="<?= htmlspecialchars($proyecto['id']) ?>"> <?= htmlspecialchars($proyecto['nombre']) ?>
                    </option>

                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-md-6">
            <label for="empleado" class="form-label">Responsable de la producción <span
                    class="text-danger">*</span></label>
            <select name="responsable_id" id="empleado" class="form-select" required>
                <option value="">Seleccione un empleado</option>
                <?php foreach ($empleados as $empleado): ?>
                    <option value="<?= htmlspecialchars($empleado['id']) ?>">
                        <?= htmlspecialchars($empleado['nombre_completo']) ?>
                    </option>

                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-12 col-md-6">
            <label for="estado_produccion" class="form-label">Estado de la producción</label>
            <select name="estado_produccion" id="estado_produccion" class="form-select">
                <option value="">Sin asociar estado</option>
                <option value="pendiente">Pendiente</option>
                <option value="en proceso">En proceso</option>
                <option value="completado">Completado</option>

            </select>
        </div>


        <div class="col-12 d-flex justify-content-between mt-3 px-4">
            <a href="index.php?vista=producciones" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar Usuario
            </button>
        </div>
    </form>
</div>

 