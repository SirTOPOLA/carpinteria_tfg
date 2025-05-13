<?php



// Obtener lista de empleados
$stmt = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo  FROM empleados ORDER BY id");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de proyectos
$stmt = $pdo->query("SELECT * FROM proyectos ");
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de clientes
$stmt = $pdo->query("SELECT * FROM clientes ");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de materiales con su precio unitario 
$stmt = $pdo->query("SELECT dc.material_id,
                            dc.precio_unitario,
                            m.nombre AS nombre_material,
                            m.stock_actual, 
                            m.stock_minimo
                        FROM detalles_compra dc
                        INNER JOIN (
                            SELECT material_id, MAX(precio_unitario) AS max_precio
                            FROM detalles_compra
                            GROUP BY material_id
                        ) AS max_dc ON dc.material_id = max_dc.material_id AND dc.precio_unitario = max_dc.max_precio
                        INNER JOIN materiales m ON dc.material_id = m.id
                        WHERE m.stock_actual > m.stock_minimo

                         ");
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<div id="content" class="container-fluid py-4">
    <h4 class="mb-4">Registrar pedido</h4>

    <form id="form" method="POST" class="row g-3 needs-validation" novalidate>
        <div class="mb-3">
            <label for="total" class="form-label">XAF(Total):</label>
            <input type="text" id="total" name="total" class="form-control" readonly>
        </div>
        <div class="col-12 col-md-6">
            <label for="clientes" class="form-label">Cliente de la solicitud <span class="text-danger">*</span></label>
            <select name="responsable_id" id="clientes" class="form-select" required>
                <option value="">Seleccione un clientes</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= htmlspecialchars($cliente['id']) ?>">
                        <?= htmlspecialchars($cliente['nombre']) ?>
                    </option>

                <?php endforeach; ?>
            </select>
        </div>

        <div class="row col-12 ">
            <div class="col-12 col-md-6 ">

                <label>
                    <input type="radio" name="opcion" value="v"> Proyectos ya existentes
                </label>


                <label>
                    <input type="radio" name="opcion" value="f"> Nuevo proyecto
                </label>

            </div>
            <div id="proyectoExistente" class="col-12 col-md-6  d-none">
                <label for="proyecto_id" class="form-label">proyecto <span class="text-danger">*</span></label>
                <select name="proyecto_id" id="proyecto" class="form-select" required>
                    <option value="">Seleccione un proyecto</option>
                    <?php foreach ($proyectos as $proyecto): ?>
                        <option value="<?= htmlspecialchars($proyecto['id']) ?>">
                            <?= htmlspecialchars($proyecto['nombre']) ?>
                        </option>

                    <?php endforeach; ?>
                </select>
            </div>
            <div id="proyectoNuevo" class=" row ">
                <div class="col-12 col-md-6">
                    <label for="nombre" class="form-label">Nombre del Proyecto</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>
                <div class="col-12 col-md-6 ">
                    <label for="estado" class="form-label">Estado actual del proyecto</label>
                    <select name="estado" id="estado" class="form-select" required>
                        <option value="">seleccione un estado..</option>
                        <option value="pendiente">pendiente</option>
                        <option value="en diseño">en diseño</option>
                        <option value="En producción">En producción</option>
                        <option value="Finalizado">Finalizado</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 ">
                    <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                    <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control">
                </div>
            </div>

        </div>



        <div class="col-12 col-md-6">
            <label for="material_id" class="form-label">Materiales <span class="text-danger">*</span></label>
            <select name="material_id" id="material" class="form-select" required>
                <option value="">Seleccione un material</option>
                <?php foreach ($materiales as $material): ?>
                    <option value="<?= htmlspecialchars($material['id']) ?>"> <?= htmlspecialchars($proyecto['nombre']) ?>
                    </option>

                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-md-6">
            <label for="total" class="form-label">XAF(Comisión):</label>
            <input type="text" id="total" class="form-control" readonly>
        </div>

        <div class="col-12 col-md-6">
            <label for="fecha_inicio" class="form-label">Fecha <span class="text-danger">*</span></label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="estado" class="form-label">Estado actual de la solicitud</label>
            <select name="estado" id="estado" class="form-select" required>
                <option value="">seleccione un estado..</option>
                <option value="cotizado">cotizado</option>
                <option value="aprobado">aprobado</option>
                <option value="rechazado">rechazado</option>
            </select>
        </div>
        <div class="col-12">
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
                                    <option class="hr" value="<?= $mat['material_id'] ?>">
                                        <?= htmlspecialchars($mat['nombre_material']) ?>
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


        </div>

        <div class="col-12 mb-3">
            <label for="descripcion" class="form-label">Detalles:</label>
            <textarea name="descripcion" class="form-control" rows="3"> </textarea>
        </div>
        <div class="col-12 d-flex justify-content-between mt-3 px-4">
            <a href="index.php?vista=pedidos" class="btn btn-secondary"><i class="bi bi-arrow-left"></i>
                Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar Usuario
            </button>
        </div>
    </form>
</div>