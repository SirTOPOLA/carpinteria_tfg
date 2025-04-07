<?php
require_once("../includes/conexion.php");

// Validar ID del proyecto
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: proyectos.php");
    exit;
}

$id = (int) $_GET['id'];
$mensaje = "";

// Obtener datos actuales del proyecto
$sql = "SELECT * FROM proyectos WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$proyecto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$proyecto) {
    header("Location: proyectos.php");
    exit;
}

// Obtener clientes para el select
$sql_clientes = "SELECT id, nombre FROM clientes ORDER BY nombre ASC";
$stmt_clientes = $pdo->query($sql_clientes);
$clientes = $stmt_clientes->fetchAll(PDO::FETCH_ASSOC);

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $cliente_id = (int) $_POST['cliente_id'];
    $estado = trim($_POST['estado']);

    // Validaciones básicas
    if ($nombre === "" || $cliente_id <= 0 || $estado === "") {
        $mensaje = "Por favor, complete todos los campos obligatorios.";
    } else {
        // Actualizar proyecto
        $sql_update = "UPDATE proyectos SET nombre = :nombre, descripcion = :descripcion, cliente_id = :cliente_id, estado = :estado WHERE id = :id";
        $stmt_update = $pdo->prepare($sql_update);
        $exito = $stmt_update->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':cliente_id' => $cliente_id,
            ':estado' => $estado,
            ':id' => $id
        ]);

        if ($exito) {
            header("Location: proyectos.php?editado=1");
            exit;
        } else {
            $mensaje = "Error al actualizar el proyecto.";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container">
        <h4 class="mb-4">Editar Proyecto</h4>

        <?php if ($mensaje): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Proyecto</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($proyecto['nombre']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control"><?= htmlspecialchars($proyecto['descripcion']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="cliente_id" class="form-label">Cliente</label>
                <select name="cliente_id" id="cliente_id" class="form-select" required>
                    <option value="">Seleccione un cliente</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente['id'] ?>" <?= $cliente['id'] == $proyecto['cliente_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cliente['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select" required>
                    <option value="pendiente" <?= $proyecto['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="en_proceso" <?= $proyecto['estado'] == 'en_proceso' ? 'selected' : '' ?>>En proceso</option>
                    <option value="completado" <?= $proyecto['estado'] == 'completado' ? 'selected' : '' ?>>Completado</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="proyectos.php" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
