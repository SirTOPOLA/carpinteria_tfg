<?php
require_once("../includes/conexion.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: compras.php?error=ID inválido");
    exit;
}

// Obtener datos de la compra
$stmt = $pdo->prepare("SELECT * FROM compras WHERE id = :id");
$stmt->execute([':id' => $id]);
$compra = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$compra) {
    header("Location: compras.php?error=Compra no encontrada");
    exit;
}

// Obtener materiales para el select
$materiales = $pdo->query("SELECT id, nombre FROM materiales ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

// Procesar envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $material_id = (int)$_POST['material_id'];
    $cantidad = (float)$_POST['cantidad'];
    $precio_total = (float)$_POST['precio_total'];

    // Validaciones simples
    if ($material_id <= 0 || $cantidad <= 0 || $precio_total <= 0) {
        $error = "Todos los campos son obligatorios y deben ser mayores a 0.";
    } else {
        $stmt = $pdo->prepare("UPDATE compras SET material_id = :material_id, cantidad = :cantidad, precio_total = :precio_total WHERE id = :id");
        $stmt->execute([
            ':material_id' => $material_id,
            ':cantidad' => $cantidad,
            ':precio_total' => $precio_total,
            ':id' => $id
        ]);
        header("Location: compras.php?mensaje=Compra actualizada");
        exit;
    }
}
?>

<?php
// dashboard.php principal
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
include '../includes/conexion.php'; // Asegúrate de tener la conexión a base de datos aquí
?>
<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid">
    <h4>Editar Compra</h4>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="material_id" class="form-label">Material</label>
            <select name="material_id" id="material_id" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($materiales as $mat): ?>
                    <option value="<?= $mat['id'] ?>" <?= $compra['material_id'] == $mat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($mat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" value="<?= htmlspecialchars($compra['cantidad']) ?>" step="0.01" min="0" required>
        </div>

        <div class="col-md-3">
            <label for="precio_total" class="form-label">Precio Total (€)</label>
            <input type="number" name="precio_total" id="precio_total" class="form-control" value="<?= htmlspecialchars($compra['precio_total']) ?>" step="0.01" min="0" required>
        </div>

        <div class="col-12 text-end">
            <a href="compras.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </form>
</div>
</main>
<?php include_once("../includes/footer.php"); ?>
