<?php
require_once("../includes/conexion.php");

function limpiar($valor) {
    return htmlspecialchars(trim($valor));
}

// Obtener ID de venta
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de venta no válido.");
}

$venta_id = (int) $_GET['id'];

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $pdo->beginTransaction();

        // Validación
        $cliente_id = (int) $_POST['cliente_id'];
        $fecha = $_POST['fecha'];
        $tipo_pago = $_POST['tipo_pago'];
        $items = $_POST['items'] ?? [];

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            throw new Exception("Fecha inválida.");
        }

        if (!in_array($tipo_pago, ['efectivo', 'tarjeta', 'transferencia'])) {
            throw new Exception("Tipo de pago inválido.");
        }

        if (empty($items)) {
            throw new Exception("Debe agregar al menos un ítem.");
        }

        $total_venta = 0;
        foreach ($items as $item) {
            if (
                !isset($item['tipo'], $item['id'], $item['cantidad'], $item['precio']) ||
                !in_array($item['tipo'], ['producto', 'proyecto', 'servicio']) ||
                !is_numeric($item['id']) || !is_numeric($item['cantidad']) || !is_numeric($item['precio'])
            ) {
                throw new Exception("Ítem inválido.");
            }

            $total_venta += $item['cantidad'] * $item['precio'];
        }

        // Actualizar venta
        $sqlVenta = "UPDATE ventas SET cliente_id = :cliente_id, fecha = :fecha, tipo_pago = :tipo_pago, total = :total WHERE id = :id";
        $stmt = $pdo->prepare($sqlVenta);
        $stmt->execute([
            ':cliente_id' => $cliente_id,
            ':fecha' => $fecha,
            ':tipo_pago' => $tipo_pago,
            ':total' => $total_venta,
            ':id' => $venta_id
        ]);

        // Eliminar ítems existentes
        $pdo->prepare("DELETE FROM venta_detalle WHERE venta_id = :id")->execute([':id' => $venta_id]);

        // Insertar nuevos ítems
        $sqlItem = "INSERT INTO venta_detalle (venta_id, tipo_item, item_id, cantidad, precio_unitario)
                    VALUES (:venta_id, :tipo_item, :item_id, :cantidad, :precio_unitario)";
        $stmtItem = $pdo->prepare($sqlItem);

        foreach ($items as $item) {
            $stmtItem->execute([
                ':venta_id' => $venta_id,
                ':tipo_item' => $item['tipo'],
                ':item_id' => $item['id'],
                ':cantidad' => $item['cantidad'],
                ':precio_unitario' => $item['precio']
            ]);
        }

        $pdo->commit();
        header("Location: ventas.php?editado=1");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<h4 class='text-danger'>Error al actualizar la venta: " . $e->getMessage() . "</h4>";
    }
}

// Obtener datos de la venta
$stmtVenta = $pdo->prepare("SELECT * FROM ventas WHERE id = ?");
$stmtVenta->execute([$venta_id]);
$venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

if (!$venta) {
    die("Venta no encontrada.");
}

// Ítems de la venta
$stmtItems = $pdo->prepare("SELECT * FROM venta_detalle WHERE venta_id = ?");
$stmtItems->execute([$venta_id]);
$venta_items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

// Datos auxiliares
$clientes = $pdo->query("SELECT id, nombre FROM clientes ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
$productos = $pdo->query("SELECT id, nombre, precio FROM productos ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
$proyectos = $pdo->query("SELECT id, nombre, costo_estimado AS precio FROM proyectos ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
$servicios = $pdo->query("SELECT id, nombre, precio FROM servicios ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-4">
    <div class="container col-sm-12 col-md-9 col-xl-8">
        <h4 class="mb-4"><i class="bi bi-pencil-square"></i> Editar Venta</h4>

        <form method="POST" id="formVenta">
            <!-- Cliente -->
            <div class="mb-3">
                <label class="form-label">Cliente:</label>
                <select name="cliente_id" class="form-select" required>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $venta['cliente_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Fecha -->
            <div class="mb-3">
                <label class="form-label">Fecha de venta:</label>
                <input type="date" name="fecha" class="form-control" value="<?= $venta['fecha'] ?>" required>
            </div>

            <!-- Tipo de ítem -->
            <div class="mb-3">
                <label class="form-label">Agregar ítem:</label>
                <select class="form-select" id="tipo_item">
                    <option value="">Seleccionar tipo</option>
                    <option value="producto">Producto</option>
                    <option value="proyecto">Proyecto</option>
                    <option value="servicio">Servicio</option>
                </select>
            </div>

            <!-- Ítems -->
            <div id="items-container" class="mb-4"></div>

            <!-- Tipo de pago -->
            <div class="mb-3">
                <label class="form-label">Tipo de pago:</label>
                <select name="tipo_pago" class="form-select" required>
                    <?php foreach (['efectivo', 'tarjeta', 'transferencia'] as $tipo): ?>
                        <option value="<?= $tipo ?>" <?= $venta['tipo_pago'] == $tipo ? 'selected' : '' ?>>
                            <?= ucfirst($tipo) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Total -->
            <div class="mb-3">
                <label class="form-label fw-bold">Total: <span id="totalVenta">0.00</span> €</label>
            </div>

            <!-- Botones -->
            <a href="ventas.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Guardar cambios
            </button>
        </form>
    </div>
</main>

<script>
const productos = <?= json_encode($productos) ?>;
const proyectos = <?= json_encode($proyectos) ?>;
const servicios = <?= json_encode($servicios) ?>;
const itemsIniciales = <?= json_encode($venta_items) ?>;

const itemsContainer = document.getElementById('items-container');
const tipoItem = document.getElementById('tipo_item');

function renderItem(item = null) {
    const index = Date.now() + Math.floor(Math.random() * 100);
    const tipo = item ? item.tipo_item : tipoItem.value;
    let lista = [];

    if (tipo === 'producto') lista = productos;
    else if (tipo === 'proyecto') lista = proyectos;
    else if (tipo === 'servicio') lista = servicios;
    else return;

    const selectedId = item ? item.item_id : '';
    const cantidad = item ? item.cantidad : 1;
    const precio = item ? item.precio_unitario : 0;

    const itemHTML = `
        <div class="card p-3 mb-3 shadow-sm">
            <input type="hidden" name="items[${index}][tipo]" value="${tipo}">
            <div class="row g-2">
                <div class="col-md-5">
                    <label class="form-label">Ítem:</label>
                    <select name="items[${index}][id]" class="form-select" onchange="actualizarPrecio(this, ${index})">
                        <option value="">Seleccionar</option>
                        ${lista.map(i => `<option value="${i.id}" data-precio="${i.precio}" ${i.id == selectedId ? 'selected' : ''}>${i.nombre}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cantidad:</label>
                    <input type="number" name="items[${index}][cantidad]" class="form-control" min="1" value="${cantidad}" onchange="recalcularTotal()">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Precio (€):</label>
                    <input type="number" name="items[${index}][precio]" class="form-control precio" step="0.01" value="${precio}" readonly>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100" onclick="this.closest('.card').remove(); recalcularTotal();">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    `;

    itemsContainer.insertAdjacentHTML('beforeend', itemHTML);
    recalcularTotal();
}

tipoItem.addEventListener('change', () => {
    renderItem();
});

function actualizarPrecio(select, index) {
    const precio = select.options[select.selectedIndex].dataset.precio || 0;
    document.querySelector(`[name="items[${index}][precio]"]`).value = precio;
    recalcularTotal();
}

function recalcularTotal() {
    let total = 0;
    document.querySelectorAll('.precio').forEach(input => {
        const row = input.closest('.row');
        const cantidad = parseFloat(row.querySelector('input[name$="[cantidad]"]').value || 1);
        const precio = parseFloat(input.value || 0);
        total += cantidad * precio;
    });
    document.getElementById('totalVenta').textContent = total.toFixed(2);
}

// Cargar ítems existentes
itemsIniciales.forEach(item => renderItem(item));
</script>

<?php include '../includes/footer.php'; ?>
