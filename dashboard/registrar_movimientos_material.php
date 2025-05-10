<?php
require_once '../includes/conexion.php';

$errores = [];
$materiales = [];
$producciones = [];
$observaciones = '';

// Obtener materiales y producciones
try {
    $stmt = $pdo->query("SELECT id, nombre, stock_actual FROM materiales ORDER BY nombre ASC");
    $materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT 
                p.id,
                pr.nombre AS nombre_proyecto,
                e.nombre AS responsable
            FROM producciones p
            INNER JOIN proyectos pr ON p.proyecto_id = pr.id
            INNER JOIN empleados e ON p.responsable_id = e.id
            ORDER BY pr.nombre ASC";
    $stmt = $pdo->query($sql);
    $producciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener materiales ya comprados o disponebles en el almacen 

    $sql = 'SELECT 
                m.nombre AS nombre_material,
                dc.cantidad AS cantidad_material
                 FROM detalles_compra dc
                 LEFT JOIN materiales m ON dc.material_id = m.id 
                 ';

    $stmt = $pdo->query($sql);
    $material_comprado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    /* print_r($material_comprado); */

} catch (PDOException $e) {
    $errores[] = "Error al cargar datos: " . $e->getMessage();
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

        <form method="POST" action="../php/guardar_movimiento_material.php" id="formMovimiento">
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de movimiento:</label>
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="">Seleccione tipo</option>
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="material_id" class="form-label">Material:</label>
                <select name="material_id" id="material_id" class="form-select" required>
                    <option value="">Seleccione tipo de movimiento primero</option>
                </select>
            </div>
            <div class="mb-3" id="stockInfo" style="display:none;">
                <small class="text-muted">Stock actual: <span id="stockActual">0</span> unidades</small>
            </div>

            <div class="mb-3" id="cantidadContainer" style="display:none;">
                <label for="cantidad" class="form-label">Cantidad:</label>
                <select name="cantidad" id="cantidad" class="form-select" required>
                    <!-- Las opciones se generan dinámicamente -->
                </select>
            </div>

            <div class="mb-3" id="mensajeErrorAjax" style="display: none;">
                <small class="text-danger" id="errorAjaxTexto"></small>
            </div>

            <div class="mb-3">
                <label for="produccion_id" class="form-label">Producción asociada:</label>
                <select name="produccion_id" id="produccion_id" class="form-select" required>
                    <option value="">Seleccione una producción</option>
                    <?php foreach ($producciones as $prod): ?>
                        <option value="<?= $prod['id'] ?>">
                            <?= htmlspecialchars($prod['nombre_proyecto']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
             
            <div class="mb-3">
                <label for="observaciones" class="form-label">Motivo / Observaciones:</label>
                <textarea name="observaciones" class="form-control"
                    rows="3"><?= htmlspecialchars($observaciones ?? '') ?></textarea>
            </div>
            <div class="d-flex justify-content-between g-3">

                <a href="movimientos_material.php" class="btn btn-secondary"> <i class="bi bi-arrow-left"></i>
                    Volver</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar movimiento</button>
            </div>
        </form>
    </div>
</main>
<?php include '../includes/footer.php'; ?>


<!-- Script para actualizar dinámicamente el stock -->
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

        function fetchStock(materialId, tipo) {
            fetch(`../ajax/obtener_stock.php?id=${materialId}&tipo=${tipo}`)
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


        function generarOpcionesCantidad(tipo) {
            cantidadSelect.innerHTML = '<option value="">Seleccione una cantidad</option>';
            const limite = tipo === 'entrada' ? 100 : currentStock;

            for (let i = 1; i <= limite; i++) {
                const option = document.createElement("option");
                option.value = i;
                option.textContent = i;
                cantidadSelect.appendChild(option);
            }

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

        tipoSelect.addEventListener("change", evaluarMostrarCantidad);
        materialSelect.addEventListener("change", evaluarMostrarCantidad);
        /* --------------------------- */

        function cargarMaterialesPorTipo(tipo) {
            fetch(`../ajax/obtener_materiales.php?tipo=${tipo}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        errorAjaxTexto.textContent = data.message || "Error al obtener materiales.";
                        mensajeError.style.display = "block";
                        return;
                    }

                    materialSelect.innerHTML = '<option value="">Seleccione un material</option>';
                    data.materiales.forEach(mat => {
                        const option = document.createElement("option");
                        option.value = mat.id;
                        option.textContent = mat.nombre;
                        materialSelect.appendChild(option);
                    });

                    materialSelect.disabled = false;
                })
                .catch(() => {
                    errorAjaxTexto.textContent = "Error al conectar con el servidor.";
                    mensajeError.style.display = "block";
                });
        }

        tipoSelect.addEventListener("change", () => {
            const tipo = tipoSelect.value;
            if (tipo) {
                cargarMaterialesPorTipo(tipo);
                cantidadContainer.style.display = "none";
                stockInfo.style.display = "none";
                materialSelect.innerHTML = '<option value="">Cargando...</option>';
                materialSelect.disabled = true;
            } else {
                materialSelect.innerHTML = '<option value="">Seleccione tipo de movimiento primero</option>';
                materialSelect.disabled = true;
                cantidadContainer.style.display = "none";
                stockInfo.style.display = "none";
            }
        });

    });


    /* -------------------- */




</script>