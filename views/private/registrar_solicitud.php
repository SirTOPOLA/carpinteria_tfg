<?php
require_once("../includes/conexion.php");


// Obtener lista de clientes
$stmt = $pdo->query("SELECT * FROM clientes ORDER BY id");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de empleados
$stmt = $pdo->query("SELECT * FROM empleados ");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="container-fluid py-4">
    <h4 class="mb-4">Registrar Produccion</h4>

    <form action="../php/guardar_producciones.php" method="POST" class="row g-3 needs-validation" novalidate>

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
            <label for="cliente_id" class="form-label">clientes <span class="text-danger">*</span></label>
            <select name="cliente_id" id="cliente" class="form-select" required>
                <option value="">Seleccione un cliente</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= htmlspecialchars($cliente['id']) ?>"> <?= htmlspecialchars($proyecto['nombre']) ?>
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
                        <?= htmlspecialchars($empleado['nombre']) ?>     <?= htmlspecialchars($empleado['apellido']) ?>
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


        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar Usuario
            </button>
            <a href="producciones.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>


<?php include '../includes/footer.php'; ?>