<?php
require_once "../includes/conexion.php";

// ========================
// VALIDACIÓN Y PROCESAMIENTO DEL FORMULARIO
// ========================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cliente_id = isset($_POST['cliente_id']) ? (int) $_POST['cliente_id'] : 0;
    $productos = $_POST['productos'] ?? [];

    // Validación robusta
    $errores = [];
    if ($cliente_id <= 0) {
        $errores[] = "Debe seleccionar un cliente válido.";
    }
    if (empty($productos)) {
        $errores[] = "Debe agregar al menos un producto a la venta.";
    }

    foreach ($productos as $producto) {
        if (empty($producto['producto_id']) || empty($producto['cantidad']) || $producto['cantidad'] <= 0) {
            $errores[] = "Todos los productos deben tener una cantidad válida.";
        }
    }

    if (empty($errores)) {
        try {
            $pdo->beginTransaction();

            $stmt_venta = $pdo->prepare("INSERT INTO ventas (cliente_id, fecha) VALUES (:cliente_id, NOW())");
            $stmt_venta->execute([':cliente_id' => $cliente_id]);
            $venta_id = $pdo->lastInsertId();

            $stmt_detalle = $pdo->prepare("INSERT INTO detalle_venta (venta_id, producto_id, cantidad) VALUES (:venta_id, :producto_id, :cantidad)");

            foreach ($productos as $producto) {
                $stmt_detalle->execute([
                    ':venta_id' => $venta_id,
                    ':producto_id' => $producto['producto_id'],
                    ':cantidad' => $producto['cantidad']
                ]);
            }

            $pdo->commit();
            header("Location: ventas.php?exito=1");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errores[] = "Error al registrar la venta: " . $e->getMessage();
        }
    }
}

// ========================
// OBTENER DATOS PARA FORMULARIO
// ========================
$stmt_clientes = $pdo->query("SELECT id, nombre FROM clientes ORDER BY nombre");
$clientes = $stmt_clientes->fetchAll(PDO::FETCH_ASSOC);

$stmt_productos = $pdo->query("SELECT id, nombre FROM productos ORDER BY nombre");
$productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../includes/header.php"; ?>

<div class="container mt-4">
    <h4>Registrar Venta</h4>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-select" required>
                <option value="">Seleccione un cliente</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <hr>
        <h5>Productos</h5>
        <div id="contenedor-productos">
            <div class="row mb-2 producto-item">
                <div class="col-md-6">
                    <select name="productos[0][producto_id]" class="form-select" required>
                        <option value="">Seleccione un producto</option>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?= $producto['id'] ?>"><?= htmlspecialchars($producto['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="productos[0][cantidad]" class="form-control" placeholder="Cantidad" min="1" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remover">&times;</button>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <button type="button" id="btn-agregar-producto" class="btn btn-secondary">+ Agregar Producto</button>
        </div>
        <div class="d-flex justify-content-between">
                    <a href="ventas.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                   
                    <button type="submit" class="btn btn-primary">Guardar Venta</button>
                </div>
    </form>
</div>

<script>
    let index = 1;
    document.getElementById('btn-agregar-producto').addEventListener('click', function () {
        const contenedor = document.getElementById('contenedor-productos');
        const item = document.querySelector('.producto-item');
        const nuevo = item.cloneNode(true);

        nuevo.querySelectorAll('select, input').forEach(el => {
            if (el.name.includes('[producto_id]')) {
                el.name = `productos[${index}][producto_id]`;
            }
            if (el.name.includes('[cantidad]')) {
                el.name = `productos[${index}][cantidad]`;
                el.value = '';
            }
        });

        contenedor.appendChild(nuevo);
        index++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remover')) {
            const items = document.querySelectorAll('.producto-item');
            if (items.length > 1) {
                e.target.closest('.producto-item').remove();
            }
        }
    });
</script>

<?php include "../includes/footer.php"; ?>
