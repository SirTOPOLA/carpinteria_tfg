<?php
 

$id = $_GET['id'] ?? null;
if (!$id) {
    die('ID no especificado');
}

 
// Obtener venta
$stmt = $pdo->prepare("SELECT * FROM ventas WHERE id = ?");
$stmt->execute([$id]);
$venta = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener detalles
$stmt = $pdo->prepare("SELECT * FROM detalles_venta WHERE venta_id = ?");
$stmt->execute([$id]);
$detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Datos auxiliares
$clientes = $pdo->query("SELECT id, nombre FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
$productos = $pdo->query("SELECT id, nombre FROM productos")->fetchAll(PDO::FETCH_ASSOC);
$servicios = $pdo->query("SELECT id, nombre FROM servicios")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h3>Editar Venta</h3>
    <form id="formEditarVenta">
        <input type="hidden" name="venta_id" value="<?= $venta['id'] ?>">

        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id'] ?>" <?= $cliente['id'] == $venta['cliente_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cliente['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="metodo_pago" class="form-label">Método de pago</label>
                <input type="text" name="metodo_pago" id="metodo_pago" class="form-control" required value="<?= htmlspecialchars($venta['metodo_pago']) ?>">
            </div>
            <div class="col-md-6">
                <label for="total_venta" class="form-label">Total (XAF)</label>
                <input type="text" id="total_venta" name="total" class="form-control text-end fw-bold" readonly value="<?= $venta['total'] ?>">
            </div>
        </div>

        <hr>

        <div class="mb-3">
            <button type="button" class="btn btn-outline-primary" onclick="agregarFila('producto')">Agregar producto</button>
            <button type="button" class="btn btn-outline-success" onclick="agregarFila('servicio')">Agregar servicio</button>
        </div>

        <table class="table table-bordered" id="tabla-detalles">
            <thead class="table-light">
                <tr>
                    <th>Tipo</th>
                    <th>Item</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $detalle): ?>
                    <tr>
                        <td>
                            <input type="hidden" name="tipo[]" value="<?= $detalle['tipo'] ?>">
                            <?= $detalle['tipo'] ?>
                        </td>
                        <td>
                            <select name="<?= $detalle['tipo'] ?>_id[]" class="form-select">
                                <?php
                                $items = $detalle['tipo'] === 'producto' ? $productos : $servicios;
                                foreach ($items as $item) {
                                    $selected = $item['id'] == $detalle['item_id'] ? 'selected' : '';
                                    echo "<option value='{$item['id']}' $selected>{$item['nombre']}</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td><input type="number" name="cantidad[]" class="form-control" value="<?= $detalle['cantidad'] ?>" min="1" required></td>
                        <td><input type="number" step="0.01" name="precio_unitario[]" class="form-control" value="<?= $detalle['precio_unitario'] ?>" required></td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">X</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="ventas.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
const productos = <?= json_encode($productos) ?>;
const servicios = <?= json_encode($servicios) ?>;

function agregarFila(tipo) {
    const tbody = document.querySelector("#tabla-detalles tbody");
    const tr = document.createElement("tr");

    let opciones = '';
    const items = tipo === 'producto' ? productos : servicios;
    items.forEach(item => {
        opciones += `<option value="${item.id}">${item.nombre}</option>`;
    });

    tr.innerHTML = `
        <td>
            <input type="hidden" name="tipo[]" value="${tipo}">
            ${tipo}
        </td>
        <td>
            <select name="${tipo}_id[]" class="form-select">${opciones}</select>
        </td>
        <td><input type="number" name="cantidad[]" class="form-control" value="1" min="1" required></td>
        <td><input type="number" step="0.01" name="precio_unitario[]" class="form-control" value="0.00" required></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">X</button></td>
    `;
    tbody.appendChild(tr);
    calcularTotalVenta();
}

function calcularTotalVenta() {
    let total = 0;
    document.querySelectorAll('#tabla-detalles tbody tr').forEach(fila => {
        const cantidad = parseFloat(fila.querySelector('input[name="cantidad[]"]').value) || 0;
        const precio = parseFloat(fila.querySelector('input[name="precio_unitario[]"]').value) || 0;
        total += cantidad * precio;
    });
    document.getElementById('total_venta').value = total.toFixed(2);
}

document.addEventListener('input', function(e) {
    if (e.target.name === 'cantidad[]' || e.target.name === 'precio_unitario[]') {
        calcularTotalVenta();
    }
});
</script>

<!-- Fetch para actualizar -->
<script>
document.getElementById('formEditarVenta').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
        const response = await fetch('api/actualizar_venta.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert(result.message);
            window.location.href = 'index.php?vista=ventas';
        } else {
            alert('Error: ' + result.message);
        }
    } catch (err) {
        console.error(err);
        alert('Error inesperado');
    }
});
</script>
