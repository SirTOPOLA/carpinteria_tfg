<?php
require_once "../includes/conexion.php";

// Validar si se está procesando el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $material_id = isset($_POST['material_id']) ? (int) $_POST['material_id'] : 0;
    $tipo = isset($_POST['tipo']) ? trim($_POST['tipo']) : '';
    $cantidad = isset($_POST['cantidad']) ? (int) $_POST['cantidad'] : 0;

    if ($id <= 0 || $material_id <= 0 || !in_array($tipo, ['entrada', 'salida']) || $cantidad <= 0) {
        die("Datos inválidos.");
    }

    $stmt = $pdo->prepare("SELECT * FROM movimientos_inventario WHERE id = ?");
    $stmt->execute([$id]);
    $movimiento_anterior = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$movimiento_anterior) die("Movimiento no encontrado.");

    $stmt = $pdo->prepare("SELECT stock FROM materiales WHERE id = ?");
    $stmt->execute([$material_id]);
    $material = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$material) die("Material no encontrado.");

    $stock_actual = $material['stock'];
    $stock_ajustado = $stock_actual;

    if ($movimiento_anterior['tipo'] === 'entrada') {
        $stock_ajustado -= $movimiento_anterior['cantidad'];
    } else {
        $stock_ajustado += $movimiento_anterior['cantidad'];
    }

    if ($tipo === 'entrada') {
        $nuevo_stock = $stock_ajustado + $cantidad;
    } else {
        if ($cantidad > $stock_ajustado) die("No hay suficiente stock para esta salida.");
        $nuevo_stock = $stock_ajustado - $cantidad;
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE materiales SET stock = ? WHERE id = ?");
        $stmt->execute([$nuevo_stock, $material_id]);

        $stmt = $pdo->prepare("UPDATE movimientos_inventario SET material_id = ?, tipo = ?, cantidad = ?, fecha = NOW() WHERE id = ?");
        $stmt->execute([$material_id, $tipo, $cantidad, $id]);

        $pdo->commit();
        header("Location: movimientos_inventario.php?mensaje=actualizado");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error al actualizar: " . $e->getMessage());
    }
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) die("ID inválido.");

$stmt = $pdo->prepare("SELECT * FROM movimientos_inventario WHERE id = ?");
$stmt->execute([$id]);
$movimiento = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$movimiento) die("Movimiento no encontrado.");

$materiales = $pdo->query("SELECT id, nombre, stock FROM materiales ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
include_once('../includes/header.php');
include_once('../includes/nav.php');
include_once('../includes/sidebar.php');

// Obtener stock ajustado actual del material seleccionado
$material_actual = null;
$stock_maximo = 1000; // Por defecto para entrada

foreach ($materiales as $mat) {
    if ($mat['id'] == $movimiento['material_id']) {
        $material_actual = $mat;
        break;
    }
}

$stock_ajustado = $material_actual['stock'];

if ($movimiento['tipo'] === 'entrada') {
    $stock_ajustado -= $movimiento['cantidad'];
} else {
    $stock_ajustado += $movimiento['cantidad'];
}

if ($movimiento['tipo'] === 'salida') {
    $stock_maximo = max($stock_ajustado, 1);
}
?>

<main class="flex-grow-1 overflow-auto p-4">
    <h2 class="mb-4">Editar Movimiento de Inventario</h2>
    <div class="container col-7 mt-4">
        <form method="POST">
            <input type="hidden" name="id" value="<?= $movimiento['id'] ?>">

            <div class="mb-3">
                <label for="material_id" class="form-label">Material</label>
                <select name="material_id" id="material_id" class="form-select" required>
                    <option value="">Seleccione un material</option>
                    <?php foreach ($materiales as $mat): ?>
                        <option value="<?= $mat['id'] ?>" <?= ($mat['id'] == $movimiento['material_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($mat['nombre']) ?> (Stock: <?= $mat['stock'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Movimiento</label>
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="">Seleccione tipo</option>
                    <option value="entrada" <?= ($movimiento['tipo'] === 'entrada') ? 'selected' : '' ?>>Entrada</option>
                    <option value="salida" <?= ($movimiento['tipo'] === 'salida') ? 'selected' : '' ?>>Salida</option>
                </select>
            </div>

            <div class="mb-3" id="cantidad-group">
                <label for="cantidad" class="form-label">Cantidad</label>
                <select name="cantidad" id="cantidad" class="form-select" required>
                    <?php for ($i = 1; $i <= $stock_maximo; $i++): ?>
                        <option value="<?= $i ?>" <?= ($i == $movimiento['cantidad']) ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="movimientos_inventario.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tipo = document.getElementById('tipo');
        const cantidadGroup = document.getElementById('cantidad-group');

        function toggleCantidad() {
            cantidadGroup.style.display = (tipo.value !== "") ? "block" : "none";
        }

        tipo.addEventListener("change", toggleCantidad);
        toggleCantidad();
    });
</script>

<?php include_once('../includes/footer.php'); ?>
