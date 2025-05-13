<?php
 

try {
    // Obtener proveedores
    $proveedores = $pdo->query("SELECT id, nombre FROM proveedores")->fetchAll(PDO::FETCH_ASSOC);

    // Obtener materiales con su categoría

    // Consulta para obtener los materiales junto con su categoría
    $materiales = $pdo->query("SELECT  *FROM materiales")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "fatal: " . $e->getMessage();
}
?>

 
<div id="content" class="container-fluid py-4">
    <div class="col-md-11">
        <h2 class="mb-4">Registrar Compra</h2>

        <form id="form" method="POST" onsubmit="return validarFormulario();">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="proveedor_id" class="form-label">Proveedor</label>
                    <select name="proveedor_id" id="proveedor_id" class="form-select" required>
                        <option value="">Seleccione un proveedor</option>
                        <?php foreach ($proveedores as $prov): ?>
                            <option value="<?= $prov['id'] ?>"><?= htmlspecialchars($prov['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="fecha" class="form-label">Fecha de compra:</label>
                    <input type="date" name="fecha" class="form-control" required>
                </div>

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
                                    <option class="hr" value="<?= $mat['id'] ?>">
                                        <?= htmlspecialchars($mat['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="number" name="cantidad[]" class="form-control" step="0.01" min="0" required
                                oninput="calcularSubtotal(this)"></td>
                        <td><input type="number" name="precio_unitario[]" class="form-control" step="0.01" min="0"
                                required oninput="calcularSubtotal(this)"></td>
                        <td><input type="text" class="form-control subtotal" readonly></td>
                        <td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)">Eliminar</button>
                        </td>
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
                <a href="index.php?vista=compras" class="btn btn-secondary"> <i class="bi bi-arrow-left"></i>Volver</a>
                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Registrar Compra</button>
            </div>

        </form>
    </div>
</div>
 

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