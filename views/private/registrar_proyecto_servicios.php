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

// Obtener lista de servicios
$servicios = $pdo->query("SELECT id, nombre FROM servicios ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servicio_id = (int)$_POST['servicio_id'];
    $costo = (float)$_POST['costo'];

    if ($servicio_id <= 0 || $costo <= 0) {
        $error = "Todos los campos son obligatorios y deben ser mayores a 0.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO servicios_proyecto (proyecto_id, servicio_id, costo) VALUES (:proyecto_id, :servicio_id, :costo)");
            $stmt->execute([
                ':proyecto_id' => $proyecto_id,
                ':servicio_id' => $servicio_id,
                ':costo' => $costo
            ]);
            $mensaje = "Servicio asignado correctamente al proyecto.";
        } catch (PDOException $e) {
            $error = "Error al asignar servicio: " . $e->getMessage();
        }
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="container-fluid py-4">
        <h4>Asignar Servicio a Proyecto</h4>
        <p><strong>Proyecto:</strong> <?= htmlspecialchars($proyecto['nombre']) ?></p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif (!empty($mensaje)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <label for="servicio_id" class="form-label">Servicio</label>
                <select name="servicio_id" id="servicio_id" class="form-select" required>
                    <option value="">Seleccione un servicio</option>
                    <?php foreach ($servicios as $serv): ?>
                        <option value="<?= $serv['id'] ?>"><?= htmlspecialchars($serv['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="costo" class="form-label">Costo (€)</label>
                <input type="number" name="costo" id="costo" class="form-control" step="0.01" min="0" required>
            </div>

            <div class="col-12 text-end">
                <a href="ver_proyecto.php?id=<?= $proyecto_id ?>" class="btn btn-secondary">Volver</a>
                <button type="submit" class="btn btn-primary">Asignar Servicio</button>
            </div>
        </form>
    </div>
 

<?php include '../includes/footer.php'; ?>
