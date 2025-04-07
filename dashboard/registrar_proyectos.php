<?php
require_once '../includes/conexion.php';

$errores = [];
$exito = '';
$clientes = [];

// Obtener lista de clientes
try {
    $stmt = $pdo->query("SELECT id, nombre FROM clientes ORDER BY nombre");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errores[] = 'Error al cargar los clientes: ' . $e->getMessage();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $cliente_id = $_POST['cliente_id'] ?? null;
    $fecha_inicio = trim($_POST['fecha_inicio'] ?? '');
    $fecha_entrega = trim($_POST['fecha_entrega'] ?? '');
    $estado = trim($_POST['estado'] ?? 'Pendiente');
    $costo_estimado = trim($_POST['costo_estimado'] ?? '');

    // Validaciones
    if (empty($nombre)) {
        $errores[] = 'El nombre del proyecto es obligatorio.';
    }

    if (!in_array($estado, ['Pendiente', 'En Progreso', 'Finalizado'])) {
        $errores[] = 'El estado del proyecto no es válido.';
    }

    if (!is_numeric($costo_estimado) || $costo_estimado < 0) {
        $errores[] = 'El costo estimado debe ser un número positivo.';
    }

    if (empty($cliente_id) || !is_numeric($cliente_id)) {
        $errores[] = 'Debe seleccionar un cliente válido.';
    }

    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO proyectos (nombre, descripcion, cliente_id, estado, fecha_inicio, fecha_entrega, costo_estimado)
                                   VALUES (:nombre, :descripcion, :cliente_id, :estado, :fecha_inicio, :fecha_entrega, :costo_estimado)");
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':cliente_id' => $cliente_id,
                ':estado' => $estado,
                ':fecha_inicio' => $fecha_inicio ?: null,
                ':fecha_entrega' => $fecha_entrega ?: null,
                ':costo_estimado' => $costo_estimado
            ]);
            $exito = 'Proyecto registrado correctamente.';
        } catch (PDOException $e) {
            $errores[] = 'Error al registrar el proyecto: ' . $e->getMessage();
        }
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container">
        <h4 class="mb-4">Registrar Proyecto</h4>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif (!empty($exito)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($exito) ?>
            </div>
        <?php endif; ?>

        <form method="POST" >
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre del Proyecto</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="cliente_id" class="form-label">Cliente</label>
                <select name="cliente_id" id="cliente_id" class="form-select" required>
                    <option value="">Seleccione un cliente</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="En Progreso">En Progreso</option>
                    <option value="Finalizado">Finalizado</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="costo_estimado" class="form-label">Costo Estimado (Bs)</label>
                <input type="number" step="0.01" name="costo_estimado" id="costo_estimado" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
            </div>

            <div class="col-md-6">
                <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control">
            </div>

            <div class="col-12">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="4"></textarea>
            </div>

            <div class="col-12 text-end">
                <a href="proyectos.php" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Registrar Proyecto</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
