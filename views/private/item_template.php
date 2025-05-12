<?php
// item_template.php

require_once("../includes/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? '';
    $index = $_POST['index'] ?? '';


    // Validar entrada
    if (!$tipo || !$index) {
        http_response_code(400);
        echo 'Datos inválidos.';
        exit;
    }

    try {
        if ($tipo === 'producto') {
            $stmtProductos = $pdo->query("SELECT id, nombre, precio, categoria_id FROM productos ORDER BY nombre");
            $productos = $stmtProductos->fetchAll(PDO::FETCH_ASSOC);
            $items = $productos;

        } elseif ($tipo === 'proyecto') {
            $stmtProyectos = $pdo->query("SELECT id, nombre , categoria_id FROM proyectos ORDER BY nombre");
            $proyectos = $stmtProyectos->fetchAll(PDO::FETCH_ASSOC);

            $items = $proyectos;
        } elseif ($tipo === 'servicio') {
            $stmtServicios = $pdo->query("SELECT id, nombre, precio FROM servicios ORDER BY nombre");
            $servicios = $stmtServicios->fetchAll(PDO::FETCH_ASSOC);
            $items = $servicios;
        } else {
            throw new Exception("Tipo no válido");
        }

        ob_start();
        ?>

        <div class="card p-3 mb-3 shadow-sm">
            <input type="hidden" name="items[<?= $index ?>][tipo]" value="<?= htmlspecialchars($tipo) ?>">
            <div class="row g-2">
                <div class="col-md-5">
                    <label class="form-label"><i class="bi bi-list-check me-1"></i> Ítem: <?= ucfirst($tipo) ?></label>
                    <select name="items[<?= $index ?>][id]" class="form-select"
                        onchange="actualizarPrecio(this, '<?= $index ?>')">
                        <option value="">Seleccionar</option>
                        <?php foreach ($items as $item): ?> 
                            <option value="<?= $item['id'] ?>" data-precio="<?= $item['precio'] ?>">
                                <?= htmlspecialchars(basename($item['nombre'])) ?>
                            </option>

                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label"><i class="bi bi-123 me-1"></i> Cantidad:</label>
                    <input type="number" name="items[<?= $index ?>][cantidad]" class="form-control" min="1" value="1"
                        onchange="recalcularTotal()">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><i class="bi bi-cash-coin me-1"></i> Precio unitario (XAF):</label>
                    <input type="number" name="items[<?= $index ?>][precio]" class="form-control precio" step="0.01" readonly>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100"
                        onclick="this.closest('.card').remove(); recalcularTotal();">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
        <?php
        echo ob_get_clean();
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo 'Error al cargar ítems: ' . $e->getMessage();
        exit;
    }
}
