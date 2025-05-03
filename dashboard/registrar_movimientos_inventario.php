<?php
require_once '../includes/conexion.php';

$errores = [];
$materiales = [];

try {
    $stmt = $pdo->query("SELECT id, nombre, stock_actual FROM materiales ORDER BY nombre ASC");
    $materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errores[] = "Error al obtener materiales: " . $e->getMessage();
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitización y validación básica
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';
    $tipo = isset($_POST['tipo']) ? trim($_POST['tipo']) : '';
    $material_id = isset($_POST['material_id']) ? (int) $_POST['material_id'] : 0;
    $cantidad = isset($_POST['cantidad']) ? (int) $_POST['cantidad'] : 0;

    // Validaciones básicas
    if (!in_array($tipo, ['entrada', 'salida'])) {
        $errores[] = "Tipo de movimiento inválido.";
    }

    if ($material_id <= 0 || $cantidad <= 0) {
        $error[] = "Datos inválidos.";
    }

    // Verificar stock_actual si el tipo es salida
    if ($tipo === 'salida') {
        try {
            $stmt = $pdo->prepare("SELECT stock_actual FROM materiales WHERE id = ?");
            $stmt->execute([$material_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                $errores[] = "Material no encontrado.";
            }

            $stock_actual = (int) $row['stock_actual'];

            if ($cantidad > $stock_actual) {
                $errores[] = "Error: No puede retirar más unidades de las disponibles. Stock actual: $stock_actual.";
            }

        } catch (PDOException $e) {
            $errores[] = "Error al consultar el stock: " . $e->getMessage();
        }
    }


   

    if (empty($errores)) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO movimientos_inventario (material_id, tipo, cantidad, motivo, fecha) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$material_id, $tipo, $cantidad, $observaciones]);

            if ($tipo === 'entrada') {
                $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual + ? WHERE id = ?")->execute([$cantidad, $material_id]);
            } else {
                $pdo->prepare("UPDATE materiales SET stock_actual = stock_actual - ? WHERE id = ?")->execute([$cantidad, $material_id]);
            }

            $pdo->commit();
            header("Location: movimientos_inventario.php");
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errores[] = "Error al guardar el movimiento: " . $e->getMessage();
        }
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<main class="flex-grow-1 overflow-auto p-4">
    <h2>Registrar Movimiento de Inventario</h2>

    <div class="container col-7 mt-4">
        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="" id="formMovimiento">
            <div class="mb-3">
                <label for="material_id" class="form-label">Material:</label>
                <select name="material_id" id="material_id" class="form-select" required>
                    <option value="">Seleccione un material</option>
                    <?php foreach ($materiales as $mat): ?>
                        <option value="<?= $mat['id'] ?>"><?= htmlspecialchars($mat['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de movimiento:</label>
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="">Seleccione tipo</option>
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
            </div>

            <div class="mb-3" id="stockInfo" style="display:none;">
                <small class="text-muted">Stock actual: <span id="stockActual">0</span> unidades</small>
            </div>

            <div class="mb-3" id="cantidadContainer" style="display:none;">
                <label for="cantidad" class="form-label">Cantidad:</label>
                <select name="cantidad" id="cantidad" class="form-select" required>
                    <!-- Las opciones se generarán por JS -->
                </select>
            </div>

            <div class="mb-3" id="mensajeErrorAjax" style="display: none;">
                <small class="text-danger" id="errorAjaxTexto"></small>
            </div>

            <div class="mb-3">
                <label for="observaciones" class="form-label">Motivo / Observaciones:</label>
                <textarea name="observaciones" class="form-control"
                    rows="3"><?= htmlspecialchars($observaciones ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Guardar movimiento</button>
        </form>
    </div>
</main>
<?php include '../includes/footer.php'; ?>

<!-- Script dinámico para mostrar stock y ajustar input de cantidad -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const tipoSelect = document.getElementById("tipo");
        const materialSelect = document.getElementById("material_id");
        const cantidadContainer = document.getElementById("cantidadContainer");
        const cantidadSelect = document.getElementById("cantidad");
        const stockInfo = document.getElementById("stockInfo");
        const stockSpan = document.getElementById("stockActual");
        const mensajeError = document.getElementById("mensajeErrorAjax");
        const errorAjaxTexto = document.getElementById("errorAjaxTexto");

        let currentStock = 0;

        // Mostrar cantidad solo si ambos están seleccionados
        function evaluarMostrarCantidad() {
            const tipo = tipoSelect.value;
            const material = materialSelect.value;

            if (tipo && material) {
                cantidadContainer.style.display = "block";
                fetchStock(material, tipo);
            } else {
                cantidadContainer.style.display = "none";
                stockInfo.style.display = "none";
                mensajeError.style.display = "none";
            }
        }

        // Obtener stock y generar options
        function fetchStock(materialId, tipo) {
            fetch(`../ajax/obtener_stock.php?id=${materialId}`)
                .then(response => response.json())
                .then(data => {
                    mensajeError.style.display = "none";

                    if (!data.success) {
                        cantidadContainer.style.display = "none";
                        stockInfo.style.display = "none";
                        errorAjaxTexto.textContent = data.message || "Error al obtener el stock.";
                        mensajeError.style.display = "block";
                        return;
                    }

                    currentStock = parseInt(data.stock_actual) || 0;
                    stockInfo.style.display = "block";
                    stockSpan.textContent = currentStock;

                    generarOpcionesCantidad(tipo);
                })
                .catch(() => {
                    cantidadContainer.style.display = "none";
                    stockInfo.style.display = "none";
                    errorAjaxTexto.textContent = "Error al conectar con el servidor.";
                    mensajeError.style.display = "block";
                });
        }

        // Generar select con opciones de cantidad
        function generarOpcionesCantidad(tipo) {
            cantidadSelect.innerHTML = '<option value="">Seleccione una cantidad</option>';

            // Para entrada: sin límite, por ejemplo hasta 100
            const limite = tipo === 'entrada' ? 100 : currentStock;

            for (let i = 1; i <= limite; i++) {
                const option = document.createElement("option");
                option.value = i;
                option.textContent = i;
                cantidadSelect.appendChild(option);
            }

            // Si no hay stock disponible en salida
            if (tipo === 'salida' && currentStock <= 0) {
                const option = document.createElement("option");
                option.textContent = "Sin stock disponible";
                option.disabled = true;
                cantidadSelect.appendChild(option);
                cantidadSelect.disabled = true;
            } else {
                cantidadSelect.disabled = false;
            }
        }

        // Listeners para evaluar y cargar dinámicamente
        tipoSelect.addEventListener("change", evaluarMostrarCantidad);
        materialSelect.addEventListener("change", evaluarMostrarCantidad);
    });
</script>