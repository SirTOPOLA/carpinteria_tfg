<?php



require_once("../includes/conexion.php");

// Función para sanitizar strings
function limpiar($valor) {
    return htmlspecialchars(trim($valor));
}

// Verificamos que se haya enviado el formulario por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Iniciar transacción
        $pdo->beginTransaction();

        // Validar cliente
        if (!isset($_POST['cliente_id']) || !is_numeric($_POST['cliente_id'])) {
            throw new Exception("Cliente no válido.");
        }
        $cliente_id = (int) $_POST['cliente_id'];

        // Validar fecha
        $fecha = $_POST['fecha'] ?? date('Y-m-d');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            throw new Exception("Fecha de venta inválida.");
        }

        // Validar tipo de pago
        $tipos_validos = ['efectivo', 'tarjeta', 'transferencia'];
        $tipo_pago = $_POST['tipo_pago'] ?? '';
        if (!in_array($tipo_pago, $tipos_validos)) {
            throw new Exception("Tipo de pago no válido.");
        }

        // Observaciones (opcional)
       // $observaciones = isset($_POST['observaciones']) ? limpiar($_POST['observaciones']) : null;

        // Validar ítems
        if (!isset($_POST['items']) || !is_array($_POST['items'])) {
            throw new Exception("Debe agregar al menos un ítem a la venta.");
        }

        $items = $_POST['items'];
        $total_venta = 0;

        // Validar cada ítem
        foreach ($items as $item) {
            if (
                !isset($item['tipo']) || !in_array($item['tipo'], ['producto', 'proyecto', 'servicio']) ||
                !isset($item['id']) || !is_numeric($item['id']) ||
                !isset($item['cantidad']) || !is_numeric($item['cantidad']) ||
                !isset($item['precio']) || !is_numeric($item['precio'])
            ) {
                throw new Exception("Datos de ítem inválidos.");
            }

            $total_venta += $item['cantidad'] * $item['precio'];
        }

        // Insertar venta
        $sqlVenta = "INSERT INTO ventas (cliente_id, fecha, tipo_pago, total) 
                     VALUES (:cliente_id, :fecha, :tipo_pago, :total)";
        $stmt = $pdo->prepare($sqlVenta);
        $stmt->execute([
            ':cliente_id' => $cliente_id,
            ':fecha' => $fecha,
            ':tipo_pago' => $tipo_pago,
            ':total' => $total_venta, 
        ]);
        $venta_id = $pdo->lastInsertId();

        // Insertar ítems
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

        // Confirmar transacción
        $pdo->commit();

        // Redirigir al listado
        header("Location: ventas.php?exito=1");
        exit;
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $pdo->rollBack();
        echo "<h4 class='text-danger'>Error al registrar la venta: " . $e->getMessage() . "</h4>";
        echo "<a href='registrar_venta.php' class='btn btn-secondary mt-3'>Volver al formulario</a>";
    }
}
  
// Obtener datos necesarios
$stmtClientes = $pdo->query("SELECT id, nombre FROM clientes ORDER BY nombre");
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

$stmtProductos = $pdo->query("SELECT id, nombre, precio, categoria_id FROM productos ORDER BY nombre");
$productos = $stmtProductos->fetchAll(PDO::FETCH_ASSOC);

$stmtProyectos = $pdo->query("SELECT id, nombre , categoria_id FROM proyectos ORDER BY nombre");
$proyectos = $stmtProyectos->fetchAll(PDO::FETCH_ASSOC);

$stmtServicios = $pdo->query("SELECT id, nombre, precio FROM servicios ORDER BY nombre");
$servicios = $stmtServicios->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>


<div class="container-fluid py-4">
    <div class="container col-sm-12 col-md-9 col-xl-8">
        <h4 class="mb-4"><i class="bi bi-cart-plus"></i> Registrar Venta</h4>
        <form  method="POST" id="formVenta">
            <!-- Cliente -->
            <div class="mb-3">
                <label for="cliente_id" class="form-label">
                    <i class="bi bi-person-check-fill me-1"></i> Cliente:
                </label>
                <select name="cliente_id" id="cliente_id" class="form-select" required>
                    <option value="">Seleccionar cliente</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Fecha -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="bi bi-calendar-event-fill me-1"></i> Fecha de venta:
                </label>
                <input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <!-- Tipo de ítem -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="bi bi-box-seam me-1"></i> Tipo de ítem:
                </label>
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
                <label class="form-label">
                    <i class="bi bi-credit-card-2-front-fill me-1"></i> Tipo de pago:
                </label>
                <select name="tipo_pago" class="form-select" required>
                    <option value="">Seleccionar</option>
                    <option value="efectivo">Efectivo</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="transferencia">Transferencia</option>
                </select>
            </div>

            <!-- Total -->
            <div class="mb-3">
                <label class="form-label fw-bold">
                    <i class="bi bi-currency-euro me-1"></i> Total: <span id="totalVenta">0.00</span> €
                </label>
            </div>

            <!-- Botón registrar -->
             <a href="ventas.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle-fill me-1"></i> Registrar Venta
            </button>
        </form>
    </div>
</div>


<script>
const productos = <?= json_encode($productos) ?>;
const proyectos = <?= json_encode($proyectos) ?>;
const servicios = <?= json_encode($servicios) ?>;

const tipoItem = document.getElementById('tipo_item');
const itemsContainer = document.getElementById('items-container');

tipoItem.addEventListener('change', () => {
    const tipo = tipoItem.value;
    let lista = [];

    if (tipo === 'producto') lista = productos;
    else if (tipo === 'proyecto') lista = proyectos;
    else if (tipo === 'servicio') lista = servicios;

    if (!tipo) return;

    const index = Date.now(); // ID único por ítem
    const itemHTML = `
        <div class="card p-3 mb-3 shadow-sm">
            <input type="hidden" name="items[${index}][tipo]" value="${tipo}">
            <div class="row g-2">
                <div class="col-md-5">
                    <label class="form-label">
                        <i class="bi bi-list-check me-1"></i> Ítem: ${tipo}
                    </label>
                    <select name="items[${index}][id]" class="form-select" onchange="actualizarPrecio(this, ${index})">
                        <option value="">Seleccionar</option>
                        ${lista.map(i => `<option value="${i.id}" data-precio="${i.precio}">${i.nombre}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">
                        <i class="bi bi-123 me-1"></i> Cantidad:
                    </label>
                    <input type="number" name="items[${index}][cantidad]" class="form-control" min="1" value="1" onchange="recalcularTotal()">
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="bi bi-cash-coin me-1"></i> Precio unitario (€):
                    </label>
                    <input type="number" name="items[${index}][precio]" class="form-control precio" step="0.01" readonly>
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
});

function actualizarPrecio(select, index) {
    const precio = select.options[select.selectedIndex].dataset.precio || 0;
    document.querySelector(`[name="items[${index}][precio]"]`).value = precio;
    recalcularTotal();
}

function recalcularTotal() {
    let total = 0;
    const items = document.querySelectorAll('.precio');

    items.forEach(precioInput => {
        const precio = parseFloat(precioInput.value || 0);
        const cantidad = parseFloat(precioInput.closest('.row').querySelector('input[name$="[cantidad]"]').value || 1);
        total += precio * cantidad;
    });

    document.getElementById('totalVenta').textContent = total.toFixed(2);
}
</script>

<?php include '../includes/footer.php'; ?>
