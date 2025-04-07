<?php
require_once '../includes/conexion.php';


// Validación básica
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar los campos principales
    $proveedor_id = isset($_POST['proveedor_id']) ? (int) $_POST['proveedor_id'] : 0;
    $material_id = $_POST['material_id'] ?? [];
    $cantidad = $_POST['cantidad'] ?? [];
    $precio_unitario = $_POST['precio_unitario'] ?? [];

    // Validación básica
    if ($proveedor_id <= 0 || empty($material_id) || count($material_id) !== count($cantidad) || count($material_id) !== count($precio_unitario)) {
        die('Datos incompletos o inválidos.');
    }

    try {
        // Iniciar transacción
        $pdo->beginTransaction();

        // Calcular total de la compra
        $total = 0;
        foreach ($material_id as $i => $mat_id) {
            $qty = (float) $cantidad[$i];
            $price = (float) $precio_unitario[$i];
            $total += $qty * $price;
        }

        // Insertar en tabla compras
        $stmt = $pdo->prepare("INSERT INTO compras (proveedor_id, total) VALUES (?, ?)");
        $stmt->execute([$proveedor_id, $total]);
        $compra_id = $pdo->lastInsertId();

        // Insertar cada material en detalle_compra
        $stmt_detalle = $pdo->prepare("INSERT INTO detalle_compra (compra_id, material_id, cantidad, precio_unitario, subtotal)
            VALUES (?, ?, ?, ?, ?)");

        // Actualizar stock del material
        $stmt_update = $pdo->prepare("UPDATE materiales SET stock = stock + ? WHERE id = ?");

        foreach ($material_id as $i => $mat_id) {
            $mat_id = (int) $mat_id;
            $qty = (float) $cantidad[$i];
            $price = (float) $precio_unitario[$i];
            $subtotal = $qty * $price;

            // Guardar detalle
            $stmt_detalle->execute([$compra_id, $mat_id, $qty, $price, $subtotal]);

            // Actualizar stock del material
            $stmt_update->execute([$qty, $mat_id]);
        }

        // Confirmar transacción
        $pdo->commit();

        
        // Ahora $materiales es un array asociativo con: id, material (nombre del material), y categoria
        header('location: compras.php');
    } catch (PDOException $e) {
        // Revertir si hay error
        $pdo->rollBack();
        die("Error al registrar la compra: " . $e->getMessage());
    }
} 

try {
    // Obtener proveedores
$proveedores = $pdo->query("SELECT id, nombre FROM proveedores")->fetchAll(PDO::FETCH_ASSOC);

// Obtener materiales con su categoría

// Consulta para obtener los materiales junto con su categoría
$materiales = $pdo->query("
    SELECT 
        m.id, 
        m.nombre AS material, 
        c.nombre AS categoria
    FROM 
        materiales m
    LEFT JOIN 
        categorias_material c ON m.categoria_id = c.id
")->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e) {
    
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
        <div class="col-md-11">
            <h2 class="mb-4">Registrar Compra</h2>

            <form  method="POST" onsubmit="return validarFormulario();">
                <div class="mb-3">
                    <label for="proveedor_id" class="form-label">Proveedor</label>
                    <select name="proveedor_id" id="proveedor_id" class="form-select" required>
                        <option value="">Seleccione un proveedor</option>
                        <?php foreach ($proveedores as $prov): ?>
                            <option value="<?= $prov['id'] ?>"><?= htmlspecialchars($prov['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <h5>Materiales</h5>
                <table class="table table-bordered" id="tabla-materiales">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="material_id[]" class="form-select" required>
                                    <option value="">Seleccione</option>
                                    <?php foreach ($materiales as $mat): ?>
                                        <option value="<?= $mat['id'] ?>">
                                            <?= htmlspecialchars($mat['material']) ?>
                                            (<?= htmlspecialchars($mat['categoria']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="number" name="cantidad[]" class="form-control" step="0.01" min="0" required
                                    oninput="calcularSubtotal(this)"></td>
                            <td><input type="number" name="precio_unitario[]" class="form-control" step="0.01" min="0"
                                    required oninput="calcularSubtotal(this)"></td>
                            <td><input type="text" class="form-control subtotal" readonly></td>
                            <td><button type="button" class="btn btn-danger"
                                    onclick="eliminarFila(this)">Eliminar</button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary mb-3" onclick="agregarFila()"> <i class="bi bi-plus"></i>
                    Agregar material</button>

                <div class="mb-3">
                    <label for="total" class="form-label">Total:</label>
                    <input type="text" id="total" name="total" class="form-control" readonly>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="compras.php" class="btn btn-secondary"> <i class="bi bi-arrow-left"></i>Volver</a>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Registrar Compra</button>
                </div>

            </form>
        </div>
    </div>
</div>
</main>
<script>
    function agregarFila() {
        const tabla = document.getElementById('tabla-materiales').getElementsByTagName('tbody')[0];
        const nuevaFila = tabla.rows[0].cloneNode(true);
        nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
        tabla.appendChild(nuevaFila);
    }

    function eliminarFila(boton) {
        const fila = boton.closest('tr');
        const tabla = document.getElementById('tabla-materiales').getElementsByTagName('tbody')[0];
        if (tabla.rows.length > 1) {
            fila.remove();
            actualizarTotal();
        }
    }

    function calcularSubtotal(input) {
        const fila = input.closest('tr');
        const cantidad = parseFloat(fila.querySelector('[name="cantidad[]"]').value) || 0;
        const precio = parseFloat(fila.querySelector('[name="precio_unitario[]"]').value) || 0;
        const subtotal = (cantidad * precio).toFixed(2);
        fila.querySelector('.subtotal').value = subtotal;
        actualizarTotal();
    }

    function actualizarTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('total').value = total.toFixed(2);
    }

    function validarFormulario() {
        const materiales = document.querySelectorAll('[name="material_id[]"]');
        for (let i = 0; i < materiales.length; i++) {
            if (materiales[i].value === "") {
                alert("Seleccione todos los materiales.");
                return false;
            }
        }
        return true;
    }
</script>