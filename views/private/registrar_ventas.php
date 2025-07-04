<?php
$productos = $pdo->query("SELECT id, nombre, precio_unitario, stock FROM productos WHERE stock > 0")->fetchAll(PDO::FETCH_ASSOC);
$servicios = $pdo->query("SELECT id, nombre, precio_base FROM servicios")->fetchAll(PDO::FETCH_ASSOC);
$consulta = $pdo->prepare("
    SELECT 
    p.*, 
    c.nombre AS cliente,
    s.nombre AS servicio,
    0 AS descuento -- Puedes reemplazarlo por un valor real si lo obtienes de otra tabla
FROM pedidos p
JOIN clientes c ON p.cliente_id = c.id
LEFT JOIN servicios s ON p.servicio_id = s.id
JOIN estados e ON p.estado_id = e.id
WHERE e.nombre = 'finalizado' AND e.entidad = 'pedido';
");
$consulta->execute();
$pedidos = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="content" class="container-fliud">
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-warning text-dark rounded-top-4 py-3">
            <h5 class="mb-0 text-white">
                <i class="bi bi-cart-check-fill me-2"></i>Registrar Venta
            </h5>
        </div>
        <div class="mb-4 text-center">
            <div class="btn-group" role="group" aria-label="Tipo de venta">
                <button type="button" class="btn btn-outline-primary active" id="btnVentaNormal">
                    <i class="bi bi-bag-check-fill me-1"></i> Venta estándar
                </button>
                <button type="button" class="btn btn-outline-success" id="btnVentaPedido">
                    <i class="bi bi-file-earmark-text-fill me-1"></i> Venta por pedido
                </button>
            </div>
        </div>

        <div class="card-body">
            <div id="seccionVentaNormal">
                <form id="formVenta" method="POST" action="guardar_venta.php" class="needs-validation" novalidate>


                    <!-- Método de pago y Total -->
                    <div class="row g-3 mb-3">
                        <!-- Sección Cliente Elegante -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold mb-2"><i
                                    class="bi bi-people-fill me-2 text-primary"></i>Tipo
                                de cliente</label>
                            <div class="btn-group w-100" role="group" aria-label="Tipo de cliente">
                                <input type="radio" class="btn-check" name="tipo_cliente" id="radio_registrado"
                                    value="registrado" checked>
                                <label class="btn btn-outline-primary" for="radio_registrado">
                                    <i class="bi bi-person-check me-1"></i> VIP
                                </label>

                                <input type="radio" class="btn-check" name="tipo_cliente" id="radio_nuevo"
                                    value="nuevo">
                                <label class="btn btn-outline-success" for="radio_nuevo">
                                    <i class="bi bi-person-plus-fill me-1"></i> Mostrador
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="total_venta" class="form-label fw-semibold">Total (XAF):</label>
                            <input type="text" id="total_venta" name="total" class="form-control text-end fw-bold"
                                readonly value="0.00">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">

                        <!-- Sección Cliente Registrado -->
                        <div class="row g-3 mb-3 align-items-end" id="cliente_registrado_section">
                            <div class="col-md-6">
                                <label for="cliente_id" class="form-label fw-semibold">Cliente registrado <span
                                        class="text-danger">*</span></label>
                                <div class="input-group shadow-sm">
                                    <select name="cliente_id" id="cliente_id" class="form-select border-end-0">
                                        <option value="">Seleccione</option>
                                        <?php
                                        $clientes = $pdo->query("SELECT id, nombre FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($clientes as $cliente):
                                            ?>
                                            <option value="<?= (int) $cliente['id'] ?>">
                                                <?= htmlspecialchars($cliente['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalCliente" title="Agregar nuevo cliente">
                                        <i class="bi bi-plus-circle fs-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Sección Cliente Nuevo -->
                        <div class="row g-3 mb-3 d-none" id="cliente_nuevo_section">
                            <div class="col-md-4">
                                <label for="nombre_cliente" class="form-label fw-semibold">Nombre completo <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nombre_cliente" id="nombre_cliente"
                                    class="form-control shadow-sm" placeholder="Ej: Juan Pérez">
                            </div>
                            <div class="col-md-4">
                                <label for="dni_cliente" class="form-label fw-semibold">DNI / Cédula <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="dni_cliente" id="dni_cliente" class="form-control shadow-sm"
                                    placeholder="Ej: 12345678">
                            </div>
                            <div class="col-md-4">
                                <label for="direccion_cliente" class="form-label fw-semibold">Dirección <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="direccion_cliente" id="direccion_cliente"
                                    class="form-control shadow-sm" placeholder="Ej: Av. Siempre Viva 742">
                            </div>
                        </div>

                    </div>

                    <hr>

                    <!-- Botones para agregar filas -->
                    <div class="mb-4 d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary flex-fill"
                            onclick="agregarFila('producto')">
                            <i class="bi bi-box-seam me-1"></i>Agregar producto
                        </button>
                        <button type="button" class="btn btn-outline-success flex-fill"
                            onclick="agregarFila('servicio')">
                            <i class="bi bi-tools me-1"></i>Agregar servicio
                        </button>
                    </div>

                    <!-- Tabla detalles -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tabla-detalles">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Item</th>
                                    <th style="width: 100px;">Cantidad</th>
                                    <th style="width: 120px;">Precio</th>
                                    <th style="width: 120px;">Descuento %</th>
                                    <th style="width: 80px;">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>


                    <!-- Botón Guardar -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?vista=ventas" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left-circle me-1"></i>Volver
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-4">
                            <i class="bi bi-save2-fill me-1"></i>Guardar Venta
                        </button>
                    </div>


                </form>
            </div>
            <div id="seccionVentaPedido" class="d-none">
                <form id="form-registrar-venta" method="POST"  class="needs-validation" novalidate>

                    <div class="mb-3">
                        <label for="pedido_id" class="form-label">Pedido del cliente:</label>
                        <select id="pedido_id" name="pedido_id" class="form-select">
                            <option value="">Seleccione un pedido</option>
                            <?php

                            foreach ($pedidos as $pedido):
                                ?>
                                <option value="<?= $pedido['id'] ?>">
                                    <?= htmlspecialchars($pedido['proyecto']) ?> -
                                    <?= htmlspecialchars($pedido['cliente']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="detallePedido" class="border rounded p-3 bg-light d-none">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Cliente:</label>
                                <input type="text" class="form-control" id="nombre_cliente_pedido" readonly>
                            </div>

                        </div>
                        <div class="row mb-4">

                            <div class="col-md-3">
                                <label class="form-label">Estimación total (XAF):</label>
                                <input type="text" class="form-control" id="estimacion_total_pedido" readonly>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Adelanto (XAF):</label>
                                <input type="text" class="form-control" id="adelanto_pedido" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Resto a pagar (XAF):</label>
                                <input type="text" class="form-control" id="resto_pedido" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Servicio asociado:</label>
                                <input type="text" class="form-control" id="servicio_pedido" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Piezas:</label>
                                <input type="number" class="form-control" id="piezas_pedido" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Precio mano de obra:</label>
                                <input type="text" class="form-control" id="precio_obra_pedido" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Descuento aplicado:</label>
                                <input type="text" class="form-control" id="descuento_pedido" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Guardar -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?vista=ventas" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left-circle me-1"></i>Volver
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-4">
                            <i class="bi bi-save2-fill me-1"></i>Guardar Venta
                        </button>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Cliente -->
<div class="modal fade" id="modalCliente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-info text-white rounded-top-4 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-lines-fill me-2"></i>Registar Cliente
                    </h5>
                </div>
                <div class="modal-body">
                    <form id="formRegistarCliente" method="POST" class="row g-3 needs-validation" novalidate>

                        <!-- Nombre completo -->
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre completo <span
                                    class="text-danger">*</span></label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" name="nombre" id="nombre" class="form-control" required>
                                <div class="invalid-feedback">El nombre es obligatorio.</div>
                            </div>
                        </div>

                        <!-- Correo -->
                        <div class="col-md-6">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                <input type="email" name="correo" id="correo" class="form-control" value=" ">
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                <input type="text" name="telefono" id="telefono" class="form-control" value=" ">
                            </div>
                        </div>
                        <!-- DIP* -->
                        <div class="col-md-6">
                            <label for="codigo" class="form-label">DIP*</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                <input type="text" name="codigo" id="codigo" class="form-control" value=" ">
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="col-md-6">
                            <label for="direccion" class="form-label">Dirección</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                                <textarea name="direccion" id="direccion" class="form-control" rows="1"> </textarea>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="col-12 d-flex justify-content-between mt-3">
                            <a href="index.php?vista=clientes" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="bi bi-arrow-left-circle me-1"></i>Volver
                            </a>
                            <button type="submit" class="btn btn-outline-success text-outline-dark rounded-pill px-4">
                                <i class="bi bi-save2-fill me-1"></i>Guardar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Producto -->
<div class="modal fade" id="modalProducto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="card border-0 bg-white rounded-4">

                <!-- Encabezado -->
                <div class="card-header bg-primary text-white rounded-top-4 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-box-seam-fill me-2"></i> Registrar Producto
                    </h5>
                </div>

                <!-- Cuerpo del modal -->
                <div class="card-body px-4 py-4">
                    <form id="formProducto" method="POST" action="guardar_producto.php" enctype="multipart/form-data"
                        class="row g-4 needs-validation" novalidate>

                        <!-- Nombre del producto -->
                        <div class="col-md-6">
                            <label for="nombre" class="form-label fw-semibold">
                                <i class="bi bi-tag-fill text-primary me-1"></i> Nombre del Producto <span
                                    class="text-danger">*</span>
                            </label>
                            <div class="input-group has-validation rounded-3 shadow-sm">
                                <span class="input-group-text bg-light"><i
                                        class="bi bi-tag-fill text-primary"></i></span>
                                <input type="text" name="nombre" id="nombre" class="form-control"
                                    placeholder="Ej: Silla de madera" required>
                                <div class="invalid-feedback">El nombre es obligatorio.</div>
                            </div>
                        </div>

                        <!-- Precio Unitario -->
                        <div class="col-md-6">
                            <label for="precio_unitario" class="form-label fw-semibold">
                                <i class="bi bi-currency-dollar text-success me-1"></i> Precio Unitario <span
                                    class="text-danger">*</span>
                            </label>
                            <div class="input-group has-validation rounded-3 shadow-sm">
                                <span class="input-group-text bg-light"><i
                                        class="bi bi-currency-dollar text-success"></i></span>
                                <input type="number" name="precio_unitario" id="precio_unitario" step="0.01"
                                    class="form-control" placeholder="Ej: 1250.50" required>
                                <div class="invalid-feedback">Ingrese un precio válido.</div>
                            </div>
                        </div>

                        <!-- Stock -->
                        <div class="col-md-6">
                            <label for="stock" class="form-label fw-semibold">
                                <i class="bi bi-boxes text-warning me-1"></i> Stock <span class="text-danger">*</span>
                            </label>
                            <div class="input-group has-validation rounded-3 shadow-sm">
                                <span class="input-group-text bg-light"><i class="bi bi-boxes text-warning"></i></span>
                                <input type="number" name="stock" id="stock" class="form-control" placeholder="Ej: 50"
                                    required>
                                <div class="invalid-feedback">Ingrese la cantidad de stock.</div>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="col-md-12">
                            <label for="descripcion" class="form-label fw-semibold">
                                <i class="bi bi-textarea-resize text-secondary me-1"></i> Descripción
                            </label>
                            <textarea name="descripcion" id="descripcion" class="form-control rounded-3 shadow-sm"
                                rows="3" placeholder="Descripción del producto..."></textarea>
                        </div>

                        <!-- Imágenes -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-image text-info me-1"></i> Imágenes del Producto
                            </label>
                            <div class="row" id="imagenesContainer"></div>

                            <button type="button" class="btn btn-outline-primary mt-2 rounded-pill shadow-sm"
                                onclick="agregarCampoImagen()">
                                <i class="bi bi-image-fill me-1"></i> Agregar Imagen
                            </button>

                            <div class="form-text">Puedes agregar una o varias imágenes (máx. 2MB cada una, formatos
                                JPG, PNG, etc.).</div>
                        </div>

                        <!-- Botones -->
                        <div class="col-12 d-flex justify-content-between mt-4">
                            <a href="index.php?vista=productos"
                                class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                                <i class="bi bi-arrow-left-circle me-1"></i> Cancelar
                            </a>

                            <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                                <i class="bi bi-save-fill me-1"></i> Registrar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Servicio -->
<div class="modal fade" id="modalServicio" tabindex="-1" aria-labelledby="modalServicioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- centrado y más ancho -->
        <div class="modal-content rounded-4 shadow-lg border-0">

            <div class="card-header bg-warning text-dark rounded-top-4 py-3 px-4 border-bottom">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-plug fs-4 me-2"></i>
                    <span class="text-white">Registrar Servicio</span>
                </h5>
            </div>

            <div class="modal-body px-4 py-4">
                <form id="formServicio" action="api/guardar_servicios.php" method="POST"
                    class="row g-4 needs-validation" novalidate>

                    <!-- Nombre del Servicio -->
                    <div class="col-md-12">
                        <label for="nombre" class="form-label">
                            <i class="bi bi-card-text me-1 text-primary"></i> Nombre del Servicio <span
                                class="text-danger">*</span>
                        </label>
                        <input type="text" name="nombre" id="nombre" class="form-control rounded-3 shadow-sm" required
                            placeholder="Ej. Corte, Pintura, Reparación">
                    </div>

                    <!-- Descripción -->
                    <div class="col-md-12">
                        <label for="descripcion" class="form-label">
                            <i class="bi bi-textarea-resize me-1 text-secondary"></i> Descripción
                        </label>
                        <textarea name="descripcion" id="descripcion" class="form-control rounded-3 shadow-sm" rows="3"
                            placeholder="Describe brevemente el servicio..."></textarea>
                    </div>

                    <!-- Precio Base -->
                    <div class="col-md-6">
                        <label for="precio_base" class="form-label">
                            <i class="bi bi-currency-dollar me-1 text-success"></i> Precio Base <span
                                class="text-danger">*</span>
                        </label>
                        <input type="number" name="precio_base" id="precio_base"
                            class="form-control rounded-3 shadow-sm" step="0.01" min="0" required
                            placeholder="Ej. 50.00">
                    </div>

                    <!-- Unidad -->
                    <div class="col-md-6">
                        <label for="unidad" class="form-label">
                            <i class="bi bi-rulers me-1 text-primary"></i> Unidad <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="unidad" id="unidad" class="form-control rounded-3 shadow-sm" required
                            placeholder="Ej. por hora, por unidad">
                    </div>

                    <!-- Estado del servicio -->
                    <div class="col-md-12">
                        <a href="#" id="toggleActivo"
                            class="btn btn-sm btn-success toggle-estado shadow-sm rounded-pill px-3" data-estado="1">
                            <i class="bi bi-toggle-on me-1"></i> Servicio Activado
                        </a>
                        <input type="hidden" name="activo" id="activo" value="1">
                    </div>

                    <!-- Botones -->
                    <div class="col-12 d-flex justify-content-between pt-4">
                        <a href="index.php?vista=servicios"
                            class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                            <i class="bi bi-save-fill me-1"></i> Guardar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<!-- 
<select name="${tipo}_id[]" class="form-select item-select"></select>
 -->
<script>
    const productos = <?= json_encode($productos) ?>;
    const servicios = <?= json_encode($servicios) ?>;

    function abrirModal(tipo) {
        const modalId = tipo === 'producto' ? 'modalProducto' : (tipo === 'servicio' ? 'modalServicio' : '');
        if (modalId) {
            const modal = new bootstrap.Modal(document.getElementById(modalId));
            modal.show();
        }
    }

    function alternarVenta() {
        const btnVentaNormal = document.getElementById('btnVentaNormal');
        const btnVentaPedido = document.getElementById('btnVentaPedido');
        const seccionNormal = document.getElementById('seccionVentaNormal');
        const seccionPedido = document.getElementById('seccionVentaPedido');

        btnVentaNormal.addEventListener('click', function () {
            btnVentaNormal.classList.add('active');
            btnVentaPedido.classList.remove('active');
            seccionNormal.classList.remove('d-none');
            seccionPedido.classList.add('d-none');
        });

        btnVentaPedido.addEventListener('click', function () {
            btnVentaPedido.classList.add('active');
            btnVentaNormal.classList.remove('active');
            seccionPedido.classList.remove('d-none');
            seccionNormal.classList.add('d-none');
        });
    }

    document.getElementById("pedido_id").addEventListener("change", function () {
        const pedidoId = this.value;

        if (!pedidoId) {
            document.getElementById("detallePedido").classList.add("d-none");
            return;
        }

        fetch("api/obtener_pedido.php?pedido_id=" + pedidoId)
            .then(res => {
                if (!res.ok) throw new Error("Respuesta no OK");
                return res.json();
            })
            .then(data => {
                if (data.error) {
                    alert("Error: " + data.error);
                    return;
                }

                document.getElementById("detallePedido").classList.remove("d-none");
                document.getElementById("nombre_cliente_pedido").value = data.cliente;
                document.getElementById("adelanto_pedido").value = data.adelanto;
                document.getElementById("resto_pedido").value = (data.estimacion_total - data.adelanto).toFixed(2);
                document.getElementById("servicio_pedido").value = data.servicio || 'N/A';
                document.getElementById("piezas_pedido").value = data.piezas;
                document.getElementById("precio_obra_pedido").value = data.precio_obra;
                document.getElementById("descuento_pedido").value = data.descuento || "0";
                document.getElementById("estimacion_total_pedido").value = parseFloat(data.estimacion_total).toFixed(2);

            })
            .catch(err => {
                console.error("Error en la solicitud:", err);
                alert("Error al obtener los datos del pedido");
            });

    });

    function guardarVentaPedido() {
        const form = document.getElementById("form-registrar-venta");

        form.addEventListener("submit", async function (e) {
            e.preventDefault(); // Prevenir recarga

            const formData = new FormData(form);

            try {
                const response = await fetch("api/guardar_venta_pedido.php", {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    // Redirigir o actualizar tabla, según tu lógica
                    window.location.href = "index.php?vista=ventas";
                } else if (result.error) {
                    alert("Error: " + result.error);
                } else {
                    alert("Error desconocido al procesar la respuesta.");
                }
            } catch (error) {
                console.error("Error en la petición:", error);
                alert("Ocurrió un error de conexión con el servidor.");
            }
        });
    }


    function agregarFila(tipo) {
        const tbody = document.querySelector("#tabla-detalles tbody");
        const tr = document.createElement("tr");

        const items = tipo === 'producto' ? productos : servicios;

        let opciones = `<option value="">Seleccione</option>`;
        items.forEach(item => {
            opciones += `<option value="${item.id}">${item.nombre}</option>`;
        });

        tr.innerHTML = `
        <td>
        <input type="hidden" name="tipo[]" value="${tipo}">

              
            ${tipo}
        </td>
        <td>
            <div class="input-group">
            <select name="item_id[]" class="form-select item-select" data-tipo="${tipo}">

                
                    ${opciones}
                </select>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="abrirModal('${tipo}')">
                    <i class="bi bi-plus-circle"></i>
                </button>
            </div>
        </td>
        <td><input type="number" name="cantidad[]" class="form-control cantidad" value="1" min="1" required></td>
        <td><input type="number" name="precio_unitario[]" class="form-control precio" step="0.01" value="0.00" required></td>
        <td><input type="number" name="descuento[]" class="form-control descuento" step="0.01" value="0.00" placeholder="%"></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); calcularTotalVenta();">X</button></td>
    `;
        tbody.appendChild(tr);
    }

    // Función para calcular el total de todos los ítems
    function calcularTotalVenta() {
        let total = 0;

        const filas = document.querySelectorAll('#tabla-detalles tbody tr');

        filas.forEach(fila => {
            const cantidad = parseFloat(fila.querySelector('.cantidad')?.value || 0);
            const precio = parseFloat(fila.querySelector('.precio')?.value || 0);
            const descuento = parseFloat(fila.querySelector('.descuento')?.value || 0); // en %

            let subtotal = cantidad * precio;
            if (descuento > 0) {
                subtotal = subtotal - (subtotal * descuento / 100);
            }

            total += subtotal;
        });

        document.getElementById('total_venta').value = total.toFixed(2);
    }

    // Detecta cambios automáticos en inputs
    document.addEventListener('input', function (e) {
        if (
            e.target.matches('input[name="cantidad[]"]') ||
            e.target.matches('input[name="precio_unitario[]"]')
        ) {
            calcularTotalVenta();
        }
    });


    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('item-select')) {
            const fila = e.target.closest('tr');
            // const tipo = fila.querySelector('input[name="tipo[]"]').value;
            const tipo = e.target.getAttribute('data-tipo');

            const id = parseInt(e.target.value);

            const lista = tipo === 'producto' ? productos : servicios;
            const item = lista.find(i => i.id == id);

            if (item) {
                const precio = tipo === 'producto' ? item.precio_unitario : item.precio_base;
                fila.querySelector('.precio').value = parseFloat(precio).toFixed(2);
                calcularTotalVenta();
            }

        }
    });

    document.getElementById('formVenta').addEventListener('submit', async function (e) {
        e.preventDefault(); // Evita el envío tradicional

        const form = e.target;
        const formData = new FormData(form);
        // console.log(form)
        //console.log(form)

        try {
            const response = await fetch('api/guardar_venta.php', {
                method: 'POST',
                body: formData
            });

            const resultado = await response.json();
            console.log(resultado)

            if (resultado.success) {
                alert('Venta registrada correctamente');
                window.location.href = 'index.php?vista=ventas';
            } else {
                alert('Error: ' + resultado.message);
            }
        } catch (error) {
            alert('Error en la solicitud: ' + error.message);
        }
    });
    // Si agregas una fila dinámicamente, vuelve a calcular
    document.getElementById('formRegistarCliente').addEventListener('submit', async function (e) {
        e.preventDefault(); // Evita el envío tradicional

        const form = e.target;
        const formData = new FormData(form);
        // console.log(form)
        //console.log(form)

        try {
            const response = await fetch('api/guardar_clientes.php', {
                method: 'POST',
                body: formData
            });

            const resultado = await response.json();
            console.log(resultado)

            if (resultado.success) {
                alert('cliente registrada correctamente');
                window.location.href = 'index.php?vista=ventas';
            } else {
                alert('Error: ' + resultado.message);
            }
        } catch (error) {
            alert('Error en la solicitud: ' + error.message);
        }
    });
    // Si agregas una fila dinámicamente, vuelve a calcular
    document.getElementById('formProducto').addEventListener('submit', async function (e) {
        e.preventDefault(); // Evita el envío tradicional

        const form = e.target;
        const formData = new FormData(form);
        // console.log(form)
        //console.log(form)

        try {
            const response = await fetch('api/guardar_productos.php', {
                method: 'POST',
                body: formData
            });

            const resultado = await response.json();
            console.log(resultado)

            if (resultado.success) {
                alert('productos registrada correctamente');
                window.location.href = 'index.php?vista=ventas';
            } else {
                alert('Error: ' + resultado.message);
            }
        } catch (error) {
            alert('Error en la solicitud: ' + error.message);
        }
    });
    document.getElementById('formServicio').addEventListener('submit', async function (e) {
        e.preventDefault(); // Evita el envío tradicional

        const form = e.target;
        const formData = new FormData(form);
        // console.log(form)
        //console.log(form)

        try {
            const response = await fetch('api/guardar_servicios.php', {
                method: 'POST',
                body: formData
            });

            const resultado = await response.json();
            console.log(resultado)

            if (resultado.success) {
                alert('servicio registrada correctamente');
                window.location.href = 'index.php?vista=ventas';
            } else {
                alert('Error: ' + resultado.message);
            }
        } catch (error) {
            alert('Error en la solicitud: ' + error.message);
        }
    });

    /* -----------ASIGNAR CLIENTE ---------- */
    const radioRegistrado = document.getElementById("radio_registrado");
    const radioNuevo = document.getElementById("radio_nuevo");
    const seccionRegistrado = document.getElementById("cliente_registrado_section");
    const seccionNuevo = document.getElementById("cliente_nuevo_section");

    function toggleCliente() {
        if (radioRegistrado.checked) {
            seccionRegistrado.classList.remove("d-none");
            seccionNuevo.classList.add("d-none");
            document.getElementById("cliente_id").required = true;
        } else {
            seccionRegistrado.classList.add("d-none");
            seccionNuevo.classList.remove("d-none");
            document.getElementById("cliente_id").required = false;
        }
    }

    radioRegistrado.addEventListener("change", toggleCliente);
    radioNuevo.addEventListener("change", toggleCliente);
    toggleCliente(); // Llamar al cargar



    // Si agregas una fila dinámicamente, vuelve a calcular
    document.addEventListener('DOMContentLoaded', (e) => {
        calcularTotalVenta()
        alternarVenta()
        guardarVentaPedido()
    }
    );
</script>