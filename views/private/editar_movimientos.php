<?php
 


$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0)
    die("ID inválido.");

$stmt = $pdo->prepare("SELECT * FROM movimientos_material WHERE id = ?");
$stmt->execute([$id]);
$movimiento = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$movimiento)
    die("Movimiento no encontrado.");

$materiales = $pdo->query("SELECT id, nombre, stock_actual FROM materiales ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

// Obtener stock ajustado actual del material seleccionado
$material_actual = null;
$stock_maximo = 1000; // Por defecto para entrada

foreach ($materiales as $mat) {
    if ($mat['id'] == $movimiento['material_id']) {
        $material_actual = $mat;
        break;
    }
}

$stock_ajustado = $material_actual['stock_actual'];

if ($movimiento['tipo_movimiento'] === 'entrada') {
    $stock_ajustado -= $movimiento['cantidad'];
} else {
    $stock_ajustado += $movimiento['cantidad'];
}

if ($movimiento['tipo_movimiento'] === 'salida') {
    $stock_maximo = max($stock_ajustado, 1);
}

//
$sql = "SELECT 
        p.id,
        pr.nombre AS nombre_proyecto,
        e.nombre AS responsable
        FROM producciones p
        INNER JOIN proyectos pr ON p.proyecto_id = pr.id
        INNER JOIN empleados e ON p.responsable_id = e.id
        ORDER BY pr.nombre ASC";
$stmt = $pdo->query($sql);
$producciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

 
   <!-- Contenido -->
   <div id="content" class="container-fluid py-4">
    <h2 class="mb-4">Editar Movimiento de Materiales</h2>
    <div class="container col-7 mt-4">
        <form id="form" method="POST">
            <input type="hidden" name="id" value="<?= $movimiento['id'] ?>">

            <div class="mb-3">
                <label for="material_id" class="form-label">Material</label>
                <select name="material_id" id="material_id" class="form-select" required>
                    <option value="">Seleccione un material</option>
                    <?php foreach ($materiales as $mat): ?>
                        <option value="<?= $mat['id'] ?>" <?= ($mat['id'] == $movimiento['material_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($mat['nombre']) ?> (Stock: <?= $mat['stock_actual'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Movimiento</label>
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="">Seleccione tipo</option>
                    <option value="entrada" <?= ($movimiento['tipo_movimiento'] === 'entrada') ? 'selected' : '' ?>>Entrada
                    </option>
                    <option value="salida" <?= ($movimiento['tipo_movimiento'] === 'salida') ? 'selected' : '' ?>>Salida
                    </option>
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
            <div class="mb-3">
                <label for="produccion_id" class="form-label">Producción asociada:</label>
                <select name="produccion_id" id="produccion_id" class="form-select" required>
                    <option value="">Seleccione una producción</option>
                    <?php foreach ($producciones as $prod): ?>
                        <option value="<?= $prod['id'] ?>">
                            <?= htmlspecialchars($prod['nombre_proyecto']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-flex justify-content-between">

                <a href="index.php?vista=movimientos" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Cambios</button>
            </div>
        </form>
    </div>
 </div>

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

 