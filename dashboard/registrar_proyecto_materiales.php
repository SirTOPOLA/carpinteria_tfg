<?php
require_once("../includes/conexion.php");

// Obtener ID del proyecto desde la URL
$proyecto_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

/* if ($proyecto_id <= 0) {
    header("Location: proyectos.php?error=ID de proyecto inválido");
    exit;
} */

// Verificar que el proyecto exista
$stmt = $pdo->prepare("SELECT * FROM proyectos WHERE id = :id");
$stmt->execute([':id' => $proyecto_id]);
$proyecto = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT pr.*, 
                                    e.nombre AS responsable
                                    FROM producciones pr 
                                    LEFT JOIN empleados e ON e.id = pr.responsable_id
                                     WHERE pr.proyecto_id = :id");
$stmt->execute([':id' => $proyecto_id]);
$produccion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produccion) {
    header("Location: registrar_producciones.php");
    exit;
}

// Obtener lista de materiales
$materiales = $pdo->query("SELECT id, nombre FROM materiales ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);


?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid">
        <h4>Asignar Material a Proyecto</h4>
        <p><strong>Proyecto:</strong> <?= htmlspecialchars($proyecto['nombre']) ?></p>
        <p><strong>Responsable:</strong> <?= htmlspecialchars($produccion['responsable']) ?></p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif (!empty($mensaje)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form action="../php/guardar_movimiento_material.php" method="POST" class="row g-3">
            <div class="col-md-6">
                <input type="hidden" name="produccion_id" value="<?= htmlspecialchars($produccion['id']) ?>">
                <input type="hidden" name="tipo" value="salida">
                <label for="material_id" class="form-label">Material</label>
                <select name="material_id" id="material_id" class="form-select" required>
                    <option value="">Seleccione un material</option>
                    <?php foreach ($materiales as $mat): ?>
                        <option value="<?= $mat['id'] ?>"><?= htmlspecialchars($mat['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" class="form-control" step="0.01" min="0" required>
            </div>
            <div class="mb-3">
                <label for="observaciones" class="form-label">Motivo / Observaciones:</label>
                <textarea name="observaciones" class="form-control" rows="3"></textarea>
            </div>

            <div class="col-12 text-end">
                <a href="proyectos.php" class="btn btn-secondary">Volver</a>
                <button type="submit" class="btn btn-primary">Asignar Material</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>