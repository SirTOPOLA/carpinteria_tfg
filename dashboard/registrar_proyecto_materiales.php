<?php
require_once("../includes/conexion.php");

// Obtener ID del proyecto desde la URL
$proyecto_id = isset($_GET['proyecto_id']) ? (int)$_GET['proyecto_id'] : 0;

if ($proyecto_id <= 0) {
    header("Location: proyectos.php?error=ID de proyecto inválido");
    exit;
}

// Verificar que el proyecto exista
$stmt = $pdo->prepare("SELECT * FROM proyectos WHERE id = :id");
$stmt->execute([':id' => $proyecto_id]);
$proyecto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$proyecto) {
    header("Location: proyectos.php?error=Proyecto no encontrado");
    exit;
}

// Obtener lista de materiales
$materiales = $pdo->query("SELECT id, nombre FROM materiales ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $material_id = (int)$_POST['material_id'];
    $cantidad = (float)$_POST['cantidad'];

    // Validaciones básicas
    if ($material_id <= 0 || $cantidad <= 0) {
        $error = "Todos los campos son obligatorios y deben ser mayores a 0.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO materiales_proyecto (proyecto_id, material_id, cantidad) VALUES (:proyecto_id, :material_id, :cantidad)");
            $stmt->execute([
                ':proyecto_id' => $proyecto_id,
                ':material_id' => $material_id,
                ':cantidad' => $cantidad
            ]);
            $mensaje = "Material asignado correctamente al proyecto.";
        } catch (PDOException $e) {
            $error = "Error al asignar material: " . $e->getMessage();
        }
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid">
        <h4>Asignar Material a Proyecto</h4>
        <p><strong>Proyecto:</strong> <?= htmlspecialchars($proyecto['nombre']) ?></p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif (!empty($mensaje)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form method="POST" class="row g-3">
            <div class="col-md-6">
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

            <div class="col-12 text-end">
                <a href="ver_proyecto.php?id=<?= $proyecto_id ?>" class="btn btn-secondary">Volver</a>
                <button type="submit" class="btn btn-primary">Asignar Material</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
