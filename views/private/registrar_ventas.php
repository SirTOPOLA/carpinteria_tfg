<?php
$productos = $pdo->query("SELECT id, nombre, precio_unitario FROM productos")->fetchAll(PDO::FETCH_ASSOC);
$servicios = $pdo->query("SELECT id, nombre, precio_base FROM servicios")->fetchAll(PDO::FETCH_ASSOC);

?>

<div id="content" class="container-fliud">
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-warning text-dark rounded-top-4 py-3">
            <h5 class="mb-0 text-white">
                <i class="bi bi-cart-check-fill me-2"></i>Registrar Venta
            </h5>
        </div>

        <div class="card-body">
            <form id="formVenta" method="POST" action="guardar_venta.php" class="needs-validation" novalidate>


                <!-- Método de pago y Total -->
                <div class="row g-3 mb-3">
                    <!-- Cliente -->
                    <div class="col-md-6 mb-3">
                        <label for="cliente_id" class="form-label fw-semibold">Cliente <span
                                class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <select name="cliente_id" id="cliente_id" class="form-select" required>
                                <option value="">Seleccione</option>
                                <?php
                                $clientes = $pdo->query("SELECT id, nombre FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($clientes as $cliente):
                                    ?>
                                    <option value="<?= (int) $cliente['id'] ?>"><?= htmlspecialchars($cliente['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#modalCliente">
                                <i class="bi bi-plus-circle"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">Por favor selecciona un cliente.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="total_venta" class="form-label fw-semibold">Total (XAF):</label>
                        <input type="text" id="total_venta" name="total" class="form-control text-end fw-bold" readonly
                            value="0.00">
                    </div>
                </div>

                <hr>

                <!-- Botones para agregar filas -->
                <div class="mb-4 d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary flex-fill" onclick="agregarFila('producto')">
                        <i class="bi bi-box-seam me-1"></i>Agregar producto
                    </button>
                    <button type="button" class="btn btn-outline-success flex-fill" onclick="agregarFila('servicio')">
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
                                <th style="width: 120px;">Descuento</th>
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
    // Si agregas una fila dinámicamente, vuelve a calcular
    document.addEventListener('DOMContentLoaded', calcularTotalVenta);
</script>