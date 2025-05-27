<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de compra no válido.");
}

$compra_id = (int) $_GET['id'];
if ($compra_id <= 0) {
    header('location: index.php?vista=compras');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM compras WHERE id = ?");
    $stmt->execute([$compra_id]);
    $compra = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$compra) {
        header('location: index.php?vista=compras');
        exit;
    }

    $proveedores = $pdo->query("SELECT id, nombre FROM proveedores ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
    $materiales = $pdo->query("SELECT id, nombre FROM materiales ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT dc.id, dc.material_id, dc.cantidad, dc.precio_unitario FROM detalles_compra dc WHERE dc.compra_id = ?");
    $stmt->execute([$compra_id]);
    $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . htmlspecialchars($e->getMessage()));
}
?>


<!-- CONTENIDO PRINCIPAL -->
<div id="content" class="container-fluid">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-bag-pencil me-2 fs-4"></i> Editar Compra: <?= htmlspecialchars($compra['codigo']) ?>
            </h4>
            <button type="button" class="btn btn-secondary" onclick="agregarCardMaterial()">
                <i class="bi bi-plus-lg"></i> Agregar material
            </button>
        </div>

        <div class="card-body">
            <form id="formCompra">
                <input type="hidden" name="id_compra" value="<?=  $compra_id ?>">
                <div  class="row">
                   
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><i class="bi bi-person"></i> Proveedor:</label>
                        <select name="proveedor_id" class="form-select" required>
                            <option value="">Seleccione proveedor</option>
                            <?php foreach ($proveedores as $prov): ?>
                                <option value="<?= $prov['id'] ?>" <?= $prov['id'] == $compra['proveedor_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($prov['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha:</label>
                        <input type="date" name="fecha" class="form-control" value="<?= $compra['fecha'] ?>" required>
                    </div>
                    <div class="col-md-4 text-end mt-3">
                        <h5 class="fw-bold">Total de la Compra: XAF/ <span id="total-compra">0.00</span></h5>
                        <input type="hidden" name="total" id="input-total">

                    </div>

                </div>

                <h5>Materiales Comprados:</h5>
                <div id="materiales-container" class="row">
                    <?php foreach ($detalles as $item): ?>
                        <div class="col-md-6 material-card">
                            <div class="border rounded p-3 mb-3 position-relative">
                                <input type="hidden" name="detalle_id[]" value="<?= $item['id'] ?>">

                                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" onclick="quitarCard(this)"></button>

                                <div class="mb-2">
                                    <label class="form-label">Material:</label>
                                    <select name="material_id[]" class="form-select" required>
                                        <option value="">Seleccione</option>
                                        <?php foreach ($materiales as $mat): ?>
                                            <option value="<?= $mat['id'] ?>" <?= $mat['id'] == $item['material_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($mat['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <label class="form-label">Cantidad:</label>
                                        <input type="number" name="cantidad[]" value="<?= $item['cantidad'] ?>" class="form-control" min="1" required>
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Precio Unitario:</label>
                                        <input type="number" name="precio_unitario[]" value="<?= $item['precio_unitario'] ?>" class="form-control" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="index.php?vista=compras" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left-circle me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-4">
                        <i class="bi bi-check-circle-fill me-1"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


 

<script>
const materialesDisponibles = <?= json_encode($materiales) ?>;

function agregarCardMaterial() {
    const contenedor = document.getElementById('materiales-container');
    const div = document.createElement('div');
    div.className = 'col-md-6 material-card';
    div.innerHTML = `
        <div class="border rounded p-3 mb-3 position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2" onclick="quitarCard(this)"></button>

            <div class="mb-2">
                <label class="form-label">Material:</label>
                <select name="material_ids[]" class="form-select" required>
                    <option value="">Seleccione</option>
                    ${materialesDisponibles.map(mat => `<option value="${mat.id}">${mat.nombre}</option>`).join('')}
                </select>
            </div>
            <div class="row">
                <div class="col">
                    <label class="form-label">Cantidad:</label>
                    <input type="number" name="cantidades[]" class="form-control" min="1" required>
                </div>
                <div class="col">
                    <label class="form-label">Precio Unitario:</label>
                    <input type="number" name="precios[]" class="form-control" step="0.01" required>
                </div>
            </div>
        </div>
    `;
    contenedor.appendChild(div);
}

function quitarCard(btn) {
    const card = btn.closest('.material-card');
    card.remove();
}
document.getElementById('formCompra').addEventListener('submit', async function (e) {
    e.preventDefault(); // Evita el envío normal del formulario
    const form = e.target;
    const formData = new FormData(form);

    try {
        const res = await fetch('api/actualizar_compra.php', {
            method: 'POST',
            body: formData
        });

        // Verificamos si la respuesta fue OK a nivel de red
        if (!res.ok) throw new Error('Error de red: ' + res.status);

        const data = await res.json();

        if (data.success) {
            alert('Compra actualizada correctamente.');
            location.href = 'index.php?vista=compras';
        } else {
            alert('Error: ' + (data.message || 'No se pudo actualizar la compra.'));
        }
    } catch (err) {
        console.error('Error en la solicitud:', err);
        alert('Error al enviar el formulario.');
    }
});






function calcularTotalCompra() {
    let total = 0;

    document.querySelectorAll('.material-card').forEach(card => {
        const cantidad = parseFloat(card.querySelector('[name^="cantidad"], [name^="cantidades"]').value) || 0;
        const precio = parseFloat(card.querySelector('[name^="precio_unitario"], [name^="precios"]').value) || 0;
        total += cantidad * precio;
    });

    document.getElementById('total-compra').textContent = total.toFixed(2);
    document.getElementById('input-total').value = total.toFixed(2);

}

// Ejecutar al cambiar inputs
document.addEventListener('input', function (e) {
    if (e.target.matches('[name^="cantidad"], [name^="cantidades"], [name^="precio_unitario"], [name^="precios"]')) {
        calcularTotalCompra();
    }
});

// Ejecutar al cargar
window.addEventListener('DOMContentLoaded', calcularTotalCompra);

</script>
