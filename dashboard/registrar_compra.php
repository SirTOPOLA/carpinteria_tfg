<?php
require_once("../includes/conexion.php");

// Guardar compra si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Validaciones básicas
        if (!isset($_POST["proveedor_id"]) || empty($_POST["proveedor_id"])) {
            throw new Exception("Seleccione un proveedor.");
        }

        if (!isset($_POST["material_id"]) || !is_array($_POST["material_id"])) {
            throw new Exception("Debe agregar al menos un material.");
        }

        // Sanitización
        $proveedor_id = intval($_POST["proveedor_id"]);
        $materiales = $_POST["material_id"];
        $cantidades = $_POST["cantidad"];
        $precios = $_POST["precio_unitario"];

        $total_compra = 0;
        $detalle_compra = [];

        // Validación de cada material
        foreach ($materiales as $i => $material_id) {
            $material_id = intval($material_id);
            $cantidad = floatval($cantidades[$i]);
            $precio_unitario = floatval($precios[$i]);

            if ($material_id <= 0 || $cantidad <= 0 || $precio_unitario < 0) {
                throw new Exception("Datos inválidos en una de las filas.");
            }

            $subtotal = $cantidad * $precio_unitario;
            $total_compra += $subtotal;

            $detalle_compra[] = [
                "material_id" => $material_id,
                "cantidad" => $cantidad,
                "precio_unitario" => $precio_unitario,
                "subtotal" => $subtotal
            ];
        }

        // Insertar en la base de datos
        $pdo->beginTransaction();

        // Insertar compra
        $stmt = $pdo->prepare("INSERT INTO compras (proveedor_id, total) VALUES (?, ?)");
        $stmt->execute([$proveedor_id, $total_compra]);
        $compra_id = $pdo->lastInsertId();

        // Insertar detalles
        $stmt_detalle = $pdo->prepare("INSERT INTO detalle_compra (compra_id, material_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
        $stmt_stock = $pdo->prepare("UPDATE materiales SET stock = stock + ? WHERE id = ?");

        foreach ($detalle_compra as $item) {
            $stmt_detalle->execute([$compra_id, $item["material_id"], $item["cantidad"], $item["precio_unitario"], $item["subtotal"]]);
            $stmt_stock->execute([$item["cantidad"], $item["material_id"]]);
        }

        $pdo->commit();

        echo "<div class='alert alert-success'>Compra registrada correctamente.</div>";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
?>

 
<?php include_once("../includes/header.php"); ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">>
    <h2>Registrar Compra</h2>
    <form method="post">
        <div class="mb-3">
            <label for="proveedor_id" class="form-label">Proveedor:</label>
            <select name="proveedor_id" id="proveedor_id" class="form-select" required>
                <option value="">Seleccione</option>
                <?php
                $proveedores = $pdo->query("SELECT id, nombre FROM proveedores ORDER BY nombre")->fetchAll();
                foreach ($proveedores as $p) {
                    echo "<option value='{$p["id"]}'>{$p["nombre"]}</option>";
                }
                ?>
            </select>
        </div>

        <table class="table table-bordered" id="detalle-compra">
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tr>
                <td>
                    <select name="material_id[]" class="form-select" required>
                        <option value="">Seleccione</option>
                        <?php
                        $materiales = $pdo->query("SELECT id, nombre FROM materiales ORDER BY nombre")->fetchAll();
                        foreach ($materiales as $m) {
                            echo "<option value='{$m["id"]}'>{$m["nombre"]}</option>";
                        }
                        ?>
                    </select>
                </td>
                <td><input type="number" name="cantidad[]" class="form-control" step="0.01" min="0" required></td>
                <td><input type="number" name="precio_unitario[]" class="form-control" step="0.01" min="0" required></td>
                <td><button type="button" onclick="eliminarFila(this)" class="btn btn-danger btn-sm">Eliminar</button></td>
            </tr>
        </table>

        <button type="button" class="btn btn-secondary mb-3" onclick="agregarFila()">Agregar Material</button>
        <br>
        <button type="submit" class="btn btn-primary">Registrar Compra</button>
    </form>
<?php
include_once("../includes/footer.php");
?>
