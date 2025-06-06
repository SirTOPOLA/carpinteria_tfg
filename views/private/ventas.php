<div id="content" class="container-fluid">
    <div class="card shadow-sm border-0 mb-4">

        <!-- Encabezado con filtros y botones -->
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h4 id="titulo" class="fw-bold text-white mb-0"><i class="bi bi-cart-check me-2"></i>Gestión de Ventas</h4>
            <div class="input-group" style="max-width: 200px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="buscador" placeholder="Buscar venta...">
            </div>

            <div id="btnGroup" class="btn-group" role="group">
                <button class="btn btn-outline-light active" onclick="mostrarTabla('ventas')">
                    <i class="bi bi-receipt me-1"></i>Ventas
                </button>
                <button class="btn btn-outline-light" onclick="mostrarTabla('facturas')">
                    <i class="bi bi-file-earmark-text me-1"></i>Facturas
                </button>
                <button class="btn btn-outline-light" onclick="mostrarTabla('pagos')">
                    <i class="bi bi-cash-coin me-1"></i>Pagos
                </button>
            </div>
        </div>

        <!-- Cuerpo de la tarjeta -->
        <div class="card-body">

            <!-- Tabla de Ventas -->
            <div id="tablaVentas" class="table-responsive">
                <div class="card-body">
                    <table class="table table-bordered table-hover align-middle table-custom mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Método Pago</th>
                                <th>Subtotal</th>
                                <th>Descuento</th>
                                <th>Total</th>
                                <th>Factura</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyVentas"></tbody>
                    </table>
                </div>
                <div class="card-footer row py-2 d-flex justify-content-between">
                    <div id="resumenVentas" class="col-12 col-md-4 text-muted small  text-center "></div>
                    <!-- Controles de paginación -->
                    <div id="paginacionVentas" class="col-12 col-md-7  d-flex justify-content-center "></div>
                </div>


            </div>

            <!-- Tabla de Facturas -->
            <div id="tablaFacturas" class="table-responsive d-none">
                <div class="card-body">
                    <table class="table table-bordered table-hover align-middle table-custom mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Fecha Venta</th>
                                <th>Fecha Emisión</th>
                                <th>Método de Pago</th>
                                <th>Monto Total</th>
                                <th>Saldo Pendiente</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyFacturas"></tbody>
                    </table>
                </div>
                <div class="card-footer row py-2 d-flex justify-content-between">
                    <div id="resumenFacturas" class="col-12 col-md-4 text-muted small  text-center "></div>
                    <!-- Controles de paginación -->
                    <div id="paginacionFacturas" class="col-12 col-md-7  d-flex justify-content-center "></div>
                </div>

            </div>

            <!-- Tabla de Pagos -->
            <div id="tablaPagos" class="table-responsive d-none">
                <div class="card-body">
                    <table class="table table-bordered table-hover align-middle table-custom mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>ID</th>
                                <th>ID Factura</th>
                                <th>Monto Pagado</th>
                                <th>Fecha de Pago</th>
                                <th>Método</th>
                                <th>Observaciones</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyPagos"></tbody>
                    </table>
                </div>
                <div class="card-footer row py-2 d-flex justify-content-between">
                    <div id="resumenPagos" class="col-12 col-md-4 text-muted small  text-center "></div>
                    <!-- Controles de paginación -->
                    <div id="paginacionPagos" class="col-12 col-md-7  d-flex justify-content-center "></div>
                </div>

            </div>
        </div>

    </div>

</div>
 


<div class="modal fade" id="modalDetallesVenta" tabindex="-1" aria-labelledby="tituloModalVenta" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-dark shadow-lg border-0">
            <div class="modal-header bg-dark ">
                <h5 class="modal-title text-white d-flex align-items-center gap-2" id="tituloModalVenta">
                    <i class="bi bi-receipt-cutoff fs-4"></i>
                    Detalles de venta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>

            <div class="modal-body bg-light-subtle" id="factura-container">
                <div class="factura p-4 bg-white rounded-4 shadow-sm mx-auto" style="max-width: 900px;">
                    <!-- Encabezado -->
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <div>
                            <img id="factura-logo" src="" alt="Logo Empresa" style="height: 70px;">
                        </div>
                        <div class="text-end">
                            <h4 class="mb-1 fw-bold" id="empresa-nombre">Empresa S.A.C.</h4>
                            <small id="empresa-direccion" class="d-block text-muted">Dirección de la empresa</small>
                            <small id="empresa-contacto" class="text-muted">Teléfono | Email</small>
                        </div>
                    </div>

                    <!-- Datos del cliente -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Cliente</h6>
                            <p id="cliente-nombre" class="mb-0 fw-semibold">Nombre del Cliente</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted mb-1">Factura #<span id="factura-numero">0000</span></h6>
                            <p class="mb-0">Fecha: <span id="factura-fecha">__/__/____</span></p>
                        </div>
                    </div>

                    <!-- Tabla de detalles -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto/Servicio</th>
                                    <th>Tipo</th>
                                    <th class="text-end">Cantidad</th>
                                    <th class="text-end">Precio Unitario</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-end">Descuento</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody id="detalle-productos">
                                <!-- Detalles cargados por JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Total -->
                    <div class="text-end mt-3 pe-1">
                        <h5 class="fw-bold">Total: <span id="factura-total">0.00</span> XAF</h5>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer d-print-none bg-body-tertiary rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cerrar
                </button>
                <!--  <button onclick="window.print()" class="btn btn-primary">
          <i class="bi bi-printer me-1"></i> Imprimir
        </button> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPagoFactura" tabindex="-1" aria-labelledby="modalPagoFacturaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow rounded-3">
            <form id="formRegistrarPago" class="needs-validation" novalidate>
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalPagoFacturaLabel">
                        <i class="bi bi-wallet2 me-2 text-success"></i> Registrar Pago
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body px-4">
                    <input type="hidden" name="factura_id" id="factura_id">

                    <div class="mb-3">
                        <label for="nombre_cliente" class="form-label fw-semibold"><i
                                class="bi bi-person-badge me-1"></i> Cliente</label>
                        <input type="text" id="nombre_cliente"
                            class="form-control-plaintext ps-2 bg-light border rounded" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="total_factura" class="form-label fw-semibold"><i class="bi bi-receipt me-1"></i>
                            Total factura</label>
                        <input type="text" id="total_factura"
                            class="form-control-plaintext ps-2 bg-light border rounded" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="saldo_pendiente" class="form-label fw-semibold"><i class="bi bi-cash-coin me-1"></i>
                            Saldo pendiente</label>
                        <input type="text" id="saldo_pendiente"
                            class="form-control-plaintext ps-2 bg-light border rounded" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="monto_pagado" class="form-label fw-semibold"><i
                                class="bi bi-currency-dollar me-1"></i> Monto a pagar</label>
                        <input type="number" name="monto_pagado" id="monto_pagado" class="form-control" min="0.01"
                            step="0.01" required>
                        <div class="invalid-feedback">Ingrese un monto válido.</div>
                    </div>

                    <div class="mb-3">
                        <label for="metodo_pago" class="form-label fw-semibold"><i
                                class="bi bi-credit-card-2-front me-1"></i> Método de pago</label>
                        <input type="text" name="metodo_pago" id="metodo_pago" class="form-control"
                            placeholder="Ej. Efectivo, Transferencia" required>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label fw-semibold"><i
                                class="bi bi-journal-text me-1"></i> Observaciones</label>
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="2"
                            placeholder="Observaciones adicionales..."></textarea>
                    </div>
                </div>

                <div class="modal-footer px-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save2 me-1"></i> Guardar pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="modalPagoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-dark shadow-lg border-0">
            <div class="modal-header bg-dark ">
                <h5 class="modal-title text-white">
                    <i class="bi bi-receipt-cutoff me-1 text-info"></i> Detalles del Pago
                </h5>

                <!--  <h5 class="modal-title text-white"><i class="bi bi-receipt"></i> Detalles del Pago</h5> -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="hidden" id="ventaId" value="">
                        <input type="hidden" id="pagoId" value="">
                        <h6 class="fw-bold text-info">Información del Pago</h6>
                        <p><i class="bi bi-hash"></i> <strong>ID:</strong> <span id="detalle-id"></span></p>
                        <p><i class="bi bi-calendar-check"></i> <strong>Fecha de pago:</strong> <span
                                id="detalle-fecha"></span></p>
                        <p><i class="bi bi-cash-stack"></i> <strong>Monto:</strong> <span id="detalle-monto"></span></p>
                        <p><i class="bi bi-credit-card"></i> <strong>Método:</strong> <span id="detalle-metodo"></span>
                        </p>
                        <p><i class="bi bi-chat-left-text"></i> <strong>Observaciones:</strong> <span
                                id="detalle-observaciones"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-success">Factura Relacionada</h6>
                        <p><i class="bi bi-hash"></i> <strong> Venta ID:</strong> <span id="detalle-id_venta"></span>
                        </p>
                        <p><strong>Factura ID:</strong> <span id="detalle-factura"></span></p>
                        <p><strong>Fecha de emisión:</strong> <span id="detalle-emision"></span></p>
                        <p><strong>Monto total:</strong> <span id="detalle-total"></span></p>
                        <p><strong>Saldo pendiente:</strong> <span id="detalle-saldo" class="text-danger"></span></p>
                        <p><strong>Estado:</strong> <span id="detalle-estado"></span></p>
                    </div>
                    <div class="col-12">
                        <h6 class="fw-bold text-secondary">Cliente</h6>
                        <p><i class="bi bi-person-circle"></i> <strong>Nombre:</strong> <span
                                id="detalle-cliente"></span></p>
                        <p><i class="bi bi-geo-alt"></i> <strong>Dirección:</strong> <span
                                id="detalle-direccion"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cerrar
                </button>
                <button type="button" class="btn btn-outline-primary" onclick="imprimirRecibo()">
                    <i class="bi bi-printer"></i> Imprimir recibo
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const buscador = document.getElementById('buscador');

    /*     document.getElementById('buscarFacturas').addEventListener('input', () => {
        cargarFacturas(1, document.getElementById('buscarFacturas').value);
    });
    
    document.getElementById('buscarPagos').addEventListener('input', () => {
        cargarPagos(1, document.getElementById('buscarPagos').value);
    }); */
    function validarFormPago() {
        const montoInput = document.getElementById('monto_pagado');
        const saldoInput = document.getElementById('saldo_pendiente');
        const formPago = document.getElementById('formRegistrarPago');

        function esMonedaValidaCEMAC(valor) {
            return /^[0-9]+(\.[0-9]{1,2})?$/.test(valor);
        }

        function mostrarError(input, mensaje) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            input.nextElementSibling.textContent = mensaje;
        }

        function mostrarExito(input) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }

        montoInput.addEventListener('input', () => {
            const monto = parseFloat(montoInput.value);
            const saldo = parseFloat(saldoInput.value.replace(/[^\d.-]/g, '')); // elimina "XAF" y otros símbolos

            if (montoInput.value.trim() === '') {
                mostrarError(montoInput, 'El monto es obligatorio.');
            } else if (!esMonedaValidaCEMAC(montoInput.value)) {
                mostrarError(montoInput, 'Ingrese un monto válido con hasta 2 decimales.');
            } else if (monto <= 0) {
                mostrarError(montoInput, 'El monto debe ser mayor que cero.');
            } else if (monto > saldo) {
                mostrarError(montoInput, 'El monto no puede superar el saldo pendiente.');
            } else if (monto % 500 !== 0) {
                mostrarError(montoInput, 'El monto debe ser múltiplo de 500 XAF.');
            } else {
                mostrarExito(montoInput);
            }
        });

        formPago.addEventListener('submit', (e) => {
            e.preventDefault();
            montoInput.dispatchEvent(new Event('input')); // fuerza revalidación

            if (!formPago.checkValidity()) {
                formPago.classList.add('was-validated');
                return;
            }

            if (montoInput.classList.contains('is-invalid')) {
                return;
            }

            // ✅ Si todo es válido, puedes enviar por AJAX o continuar:
            console.log('Formulario válido y listo para enviar.');
            /*  formPago.submit(); // si no usas AJAX */
        });
    }


    manejarEventosAjaxTbody()
    function cargarVentas(pagina = 1, termino = '') {
        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('termino', termino);

        fetch('api/listar_venta.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('tbodyVentas').innerHTML = data.html;
                    document.getElementById('paginacionVentas').innerHTML = data.paginacion;
                    document.getElementById('resumenVentas').textContent = data.resumen;
                    // inicializarBotonesDetalle(); // 👈 volver a asociar eventos
                }
            });
    }


    function cargarFacturas(pagina = 1, termino = '') {
        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('termino', termino);
        fetch('api/listar_factura.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    document.getElementById('tbodyFacturas').innerHTML = data.html;
                    document.getElementById('paginacionFacturas').innerHTML = data.paginacion;
                    document.getElementById('resumenFacturas').textContent = data.resumen;
                    // inicializarBotonesDetalle(); // 👈 volver a asociar eventos
                }
            })
            .catch(err => console.error('Error al cargar facturas:', err));
    }

    function cargarPagos(pagina = 1, termino = '') {
        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('termino', termino);
        fetch('api/listar_pagos.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    document.getElementById('tbodyPagos').innerHTML = data.html;
                    document.getElementById('paginacionPagos').innerHTML = data.paginacion;
                    document.getElementById('resumenPagos').textContent = data.resumen;
                    //inicializarBotonesDetalle(); // 👈 volver a asociar eventos
                } else {

                }
            })
            .catch(err => console.error('Error al cargar pagos:', err));
    }

    function manejarEventosAjaxTbody() {
        document.getElementById("tbodyVentas").addEventListener("click", function (e) {
            //eliminar un registro de la fila por ID            
            if (e.target.closest(".btn-eliminar")) {
                const id = e.target.closest(".btn-eliminar").dataset.id;
                eliminar(id);

            }
            if (e.target.closest('.btn-emitir-factura')) {
                const btn = e.target.closest('.btn-emitir-factura');
                const ventaId = btn.dataset.id;
                emitirFactura(ventaId);
            }
            if (e.target.closest('.btn-toggle')) {
                const btn = e.target.closest('.btn-toggle');
                const ventaId = btn.dataset.id;
                detallesVenta(ventaId);
            }
        });
        document.getElementById('tbodyFacturas').addEventListener('click', (e) => {
            const btn = e.target.closest('.btn-registrar-pago');

            if (btn && !btn.disabled) {
                const id = btn.dataset.id;
                const total = btn.dataset.total;
                const pendiente = btn.dataset.pendiente;
                const cliente = btn.dataset.cliente;
                facturar(id, total, pendiente, cliente);
            }
        });



        document.getElementById('tbodyPagos').addEventListener('click', async (e) => {
            if (e.target.closest('.ver-pago')) {
                e.preventDefault();
                const pagoId = e.target.closest('.ver-pago').dataset.id;
                verPagos(pagoId);
            }
        })


    }

    async function verPagos(pagoId) {
        const formData = new FormData();
        formData.append('id', pagoId);


        try {
            const response = await fetch('api/obtener_pago.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json(); // CORREGIDO: antes decía `res.json()`


            if (data.success) {
                const p = data.pago;

                document.getElementById('pagoId').value = p.id;
                document.getElementById('ventaId').value = p.id_venta;
                document.getElementById('detalle-id_venta').textContent = p.id_venta;
                document.getElementById('detalle-id').textContent = p.id;
                document.getElementById('detalle-factura').textContent = p.factura_id;
                document.getElementById('detalle-monto').textContent = 'XAF ' + parseFloat(p.monto_pagado).toFixed(2);
                document.getElementById('detalle-fecha').textContent = p.fecha_pago;
                document.getElementById('detalle-metodo').textContent = p.metodo_pago || '—';
                document.getElementById('detalle-observaciones').textContent = p.observaciones || '—';
                document.getElementById('detalle-emision').textContent = p.fecha_emision || '—';
                document.getElementById('detalle-total').textContent = 'XAF ' + parseFloat(p.monto_total).toFixed(2);
                document.getElementById('detalle-saldo').textContent = '- XAF ' + parseFloat(p.saldo_pendiente).toFixed(2);
                document.getElementById('detalle-cliente').textContent = p.nombre_cliente || '—';
                document.getElementById('detalle-direccion').textContent = p.direccion_cliente || '—';

                const estadoBadge = document.createElement('span');
                estadoBadge.className = 'badge rounded-pill bg-' + (
                    p.estado_factura.toLowerCase().includes('pend') ? 'warning text-dark' :
                        p.estado_factura.toLowerCase().includes('pag') ? 'success' : 'secondary'
                );
                estadoBadge.textContent = p.estado_factura || '—';

                const estadoCont = document.getElementById('detalle-estado');
                estadoCont.innerHTML = '';
                estadoCont.appendChild(estadoBadge);

                new bootstrap.Modal(document.getElementById('modalPago')).show();
            } else {
                alert(data.mensaje || 'Pago no encontrado.');
            }
        } catch (err) {
            console.error('Error al obtener detalle:', err);
            alert('Error al cargar detalles.');
        }
    }


    /* ---------- MOSTRAR DETALLES DE LA VENTA EN UN MODAL --------- */
    async function detallesVenta(ventaId) {
        const modalElement = document.getElementById('modalDetallesVenta');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();

        // Elementos de destino
        const logo = document.getElementById('factura-logo');
        const nombreEmpresa = document.getElementById('empresa-nombre');
        const direccionEmpresa = document.getElementById('empresa-direccion');
        const contactoEmpresa = document.getElementById('empresa-contacto');
        const clienteNombre = document.getElementById('cliente-nombre');
        const facturaNumero = document.getElementById('factura-numero');
        const facturaFecha = document.getElementById('factura-fecha');
        const facturaTotal = document.getElementById('factura-total');
        const detalleProductos = document.getElementById('detalle-productos');

        // Limpiar contenido anterior
        logo.src = '';
        nombreEmpresa.textContent = '';
        direccionEmpresa.textContent = '';
        contactoEmpresa.textContent = '';
        clienteNombre.textContent = '';
        facturaNumero.textContent = '';
        facturaFecha.textContent = '';
        facturaTotal.textContent = '';
        detalleProductos.innerHTML = '';

        try {
            const formData = new FormData();
            formData.append('id', ventaId);

            const response = await fetch('api/detalles_venta.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!data.success) {
                detalleProductos.innerHTML = `
                <tr><td colspan="7" class="text-center text-danger">${data.mensaje || 'No se encontraron datos.'}</td></tr>
            `;
                return;
            }

            const { venta, detalles, config } = data;

            // Empresa
            nombreEmpresa.textContent = config.nombre || '';
            direccionEmpresa.textContent = config.direccion || '';
            contactoEmpresa.textContent = `${config.telefono || ''} | ${config.email || ''}`;
            if (config.logo) {
                logo.src = 'api/' + config.logo;
            }

            // Factura
            clienteNombre.textContent = venta.cliente ?? venta.nombre_cliente ?? 'Cliente no identificado';
            facturaNumero.textContent = venta.id;
            facturaFecha.textContent = new Date(venta.fecha).toLocaleDateString();
            facturaTotal.textContent = parseFloat(venta.total).toFixed(2);

            // Detalles
            if (detalles.length === 0) {
                detalleProductos.innerHTML = `<tr><td colspan="7" class="text-center text-muted">Sin productos registrados.</td></tr>`;
                return;
            }

            for (const item of detalles) {
                const subtotal = item.cantidad * item.precio_unitario;
                const fila = `
                <tr>
                    <td>${item.nombre}</td>
                    <td>${item.tipo}</td>
                    <td class="text-end">${item.cantidad}</td>
                    <td class="text-end">XAF ${parseFloat(item.precio_unitario).toFixed(2)}</td>
                    <td class="text-end">XAF ${subtotal.toFixed(2)}</td>
                    <td class="text-end">${parseFloat(item.descuento).toFixed(2)}%</td>
                    <td class="text-end">XAF ${parseFloat(item.subtotal).toFixed(2)}</td>
                </tr>
            `;
                detalleProductos.insertAdjacentHTML('beforeend', fila);
            }

        } catch (error) {
            console.error('Error cargando detalles de venta:', error);
            detalleProductos.innerHTML = `
            <tr><td colspan="7" class="text-center text-danger">Error al cargar los detalles de la venta.</td></tr>
        `;
        }
    }
    function facturar(id, total, pendiente, cliente) {
        const modal = new bootstrap.Modal(document.getElementById('modalPagoFactura'));
        const formPago = document.getElementById('formRegistrarPago');
        document.getElementById('factura_id').value = id;
        document.getElementById('nombre_cliente').value = cliente;
        document.getElementById('total_factura').value = `XAF ${parseFloat(total).toFixed(2)}`;
        document.getElementById('saldo_pendiente').value = `XAF ${parseFloat(pendiente).toFixed(2)}`;
        document.getElementById('monto_pagado').value = "";

        modal.show(); // Esto sí abrirá correctamente si el modal existe en el DOM
        formPago.addEventListener('submit', async (e) => {
            e.preventDefault();
            (() => {
                const forms = document.querySelectorAll('.needs-validation');
                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            })();

            const formData = new FormData(formPago);
            const response = await fetch('api/guardar_pago.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert("Pago registrado correctamente.");
                modal.hide();
                location.reload();
            } else {
                alert("Error: " + result.message);
            }
        });
    }



    async function eliminar(id) {
        if (confirm('¿Seguro que quieres eliminar esta venta?')) {
            try {
                const formData = new FormData();
                formData.append('id', id);

                const response = await fetch('api/eliminar_ventas.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Error al eliminar la venta.');
                }
            } catch (error) {
                alert('Error en la petición: ' + error.message);
            }
        }
    }



    function emitirFactura(ventaId) {
        if (!confirm(`¿Deseas emitir la factura para esta venta? ${ventaId}`)) return;

        const formData = new FormData();
        formData.append('venta_id', ventaId);

        fetch('api/emitir_factura.php', {
            method: 'POST',
            body: formData
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert(data.mensaje);
                    location.reload(); // O recargar solo la tabla con AJAX si lo usas
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error al emitir factura');
            });

    }

    function mostrarTabla(tabla) {
        // Ocultar todas las tablas
        document.getElementById("tablaVentas").classList.add("d-none");
        document.getElementById("tablaFacturas").classList.add("d-none");
        document.getElementById("tablaPagos").classList.add("d-none");

        // Quitar clase 'active' de todos los botones
        document.querySelectorAll(".btn-group .btn").forEach(btn => btn.classList.remove("active"));

        // Referencia al contenedor de botones
        const btnGroup = document.getElementById('btnGroup');

        // Eliminar botón "Nuevo Venta" si existe
        const botonExistente = document.getElementById("btnNuevoVenta");
        if (botonExistente) {
            botonExistente.remove();
        }

        if (tabla === "ventas") {
            document.getElementById("tablaVentas").classList.remove("d-none");
            document.querySelectorAll(".btn-group .btn")[0].classList.add("active");
            document.getElementById('titulo').textContent = 'Gestión de Ventas';

            // Crear y agregar botón "Nuevo Venta"
            const nuevoBtn = document.createElement('a');
            nuevoBtn.href = 'index.php?vista=registrar_ventas';
            nuevoBtn.className = 'btn btn-secondary';
            nuevoBtn.id = 'btnNuevoVenta';
            nuevoBtn.innerHTML = '<i class="bi bi-plus"></i> Nuevo Venta';

            // Insertar como primer hijo del grupo
            btnGroup.insertBefore(nuevoBtn, btnGroup.firstChild);

        } else if (tabla === "facturas") {
            document.getElementById("tablaFacturas").classList.remove("d-none");
            document.querySelectorAll(".btn-group .btn")[1].classList.add("active");
            document.getElementById('titulo').textContent = 'Gestión de Facturas';
            // Botón ya eliminado arriba si existía
        } else if (tabla === "pagos") {
            document.getElementById("tablaPagos").classList.remove("d-none");
            document.querySelectorAll(".btn-group .btn")[2].classList.add("active");
            document.getElementById('titulo').textContent = 'Gestión de Pagos';
            // Botón ya eliminado arriba si existía
        }
    }



    async function imprimirRecibo() {
        console.log(document.getElementById('ventaId').value)
        const idVenta = document.getElementById('ventaId').value;
        console.log(document.getElementById('pagoId').value)
        const pagoId = document.getElementById('pagoId').value;
        try {
            // 1. Obtener detalles del pago
            const formPago = new FormData();
            formPago.append('id', pagoId);

            const resPago = await fetch('api/obtener_pago.php', {
                method: 'POST',
                body: formPago
            });
            const dataPago = await resPago.json();

            if (!dataPago.success) {
                alert('Error al obtener el pago: ' + dataPago.mensaje);
                return;
            }

            const p = dataPago.pago;
            const totalPagadoAcumulado = parseFloat(dataPago.acumulado || 0).toFixed(2);

            // 2. Obtener configuración de empresa
            const resConfig = await fetch('api/obtener_config.php');
            const config = await resConfig.json();

            // 3. Obtener detalles de la venta
            const formVenta = new FormData();
            formVenta.append('id', p.id_venta);

            const resVenta = await fetch('api/detalles_venta.php', {
                method: 'POST',
                body: formVenta
            });
            const dataVenta = await resVenta.json();

            if (!dataVenta.success) {
                alert('Error al obtener detalles de venta: ' + dataVenta.mensaje);
                return;
            }

            const venta = dataVenta.venta;
            const detalles = dataVenta.detalles;
            const fechaHoy = new Date().toLocaleDateString();

            // 4. Construir HTML
            const html = `
        <html>
        <head>
            <title>Detalle de Pago y Recibo</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
            <style>
                body { font-family: Arial, sans-serif; margin: 30px; color: #000; }
                .titulo { font-weight: bold; font-size: 1.2rem; margin-top: 1.5rem; }
                .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                .table th, .table td { padding: 8px; border: 1px solid #dee2e6; }
                .table-light { background-color: #f8f9fa; }
                .text-end { text-align: right; }
                hr { margin: 2rem 0; }
            </style>
        </head>
        <body>
            <div class="text-center mb-3">
                <h5 class="text-success"><i class="bi bi-building"></i> ${config.nombre_empresa || ''}</h5>
                <p>
                    <strong>Dirección:</strong> ${config.direccion || ''} |
                    <strong>Tel:</strong> ${config.telefono || ''} |
                    <strong>Correo:</strong> ${config.correo || ''} |
                    <strong>NIF:</strong> ${config.nif || ''}
                </p>
            </div>

            <div class="titulo">Detalles del Pago</div>
            <table class="table table-sm">
                <tbody>
                    <tr><th>ID Pago</th><td>${p.id}</td></tr>
                    <tr><th>Factura</th><td>${p.factura_id}</td></tr>
                    <tr><th>Monto Pagado</th><td>XAF ${parseFloat(p.monto_pagado).toFixed(2)}</td></tr>
<tr><th>Total Pagado Acumulado</th><td><strong> + XAF ${totalPagadoAcumulado}</strong></td></tr>
 <tr><th>Monto pendiente</th><td> - XAF <span class = 'text-danger'> ${parseFloat(p.saldo_pendiente).toFixed(2)}</span></td></tr>
                    <tr><th>Fecha de Pago</th><td>${p.fecha_pago}</td></tr>
                    <tr><th>Método</th><td>${p.metodo_pago || '—'}</td></tr>
                    <tr><th>Observaciones</th><td>${p.observaciones || '—'}</td></tr>
                    <tr><th>Estado Factura</th><td>${p.estado_factura}</td></tr>
                </tbody>
            </table>

            <hr>

            <div class="titulo">Datos del Cliente</div>
            <p>
                <strong>Nombre:</strong> ${p.nombre_cliente}<br>
                <strong>Dirección:</strong> ${p.direccion_cliente}
            </p>

            <div class="titulo">Detalle de la Venta</div>
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Producto / Servicio</th>
                        <th>Tipo</th>
                        <th class="text-end">Cant.</th>
                        <th class="text-end">Precio</th>
                        <th class="text-end">Desc.</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    ${detalles.map((item, i) => `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${item.nombre}</td>
                            <td>${item.tipo}</td>
                            <td class="text-end">${item.cantidad}</td>
                            <td class="text-end">${item.precio_unitario}</td>
                            <td class="text-end">${item.descuento || 0}</td>
                            <td class="text-end">${item.subtotal}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>

            <div class="text-end mt-3 fw-bold">
                Total: ${venta.total} ${config.moneda}
            </div>

            <hr>
            <div class="text-end">
                <small><em>Generado: ${fechaHoy}</em></small>
            </div>
        </body>
        </html>
        `;

            // 5. Imprimir
            const ventana = window.open('', 'Recibo Completo', 'width=900,height=800');
            ventana.document.write(html);
            ventana.document.close();
            ventana.focus();
            ventana.print();
            ventana.close();

        } catch (error) {
            console.error('Error al generar impresión:', error);
            alert('Ocurrió un error al generar la hoja.');
        }
    }


    document.addEventListener('DOMContentLoaded', () => {
        cargarVentas();
        cargarFacturas();
        cargarPagos();
        validarFormPago();
        buscador.addEventListener('keyup', function () {
            const termino = this.value.trim();
            cargarVentas(1, termino);
            cargarPagos(1, termino);
            cargarFacturas(1, termino);
        });

        document.getElementById('paginacionVentas').addEventListener('click', function (e) {
            if (e.target.classList.contains('pagina-link')) {
                const pagina = e.target.dataset.pagina;
                const termino = buscador.value.trim();
                cargarVentas(pagina, termino);
                /* cargarFacturas(pagina, termino);
                cargarPagos(pagina, termino); */
            }
        });
        document.getElementById('paginacionFacturas').addEventListener('click', function (e) {
            if (e.target.classList.contains('pagina-link')) {
                const pagina = e.target.dataset.pagina;
                const termino = buscador.value.trim();
                //cargarVentas(pagina, termino);
                 cargarFacturas(pagina, termino);
                //cargarPagos(pagina, termino); 
            }
        });
        document.getElementById('paginacionPagos').addEventListener('click', function (e) {
            if (e.target.classList.contains('pagina-link')) {
                const pagina = e.target.dataset.pagina;
                const termino = buscador.value.trim();
                //cargarVentas(pagina, termino);
                //cargarFacturas(pagina, termino);
                cargarPagos(pagina, termino); 
            }
        });
    });


</script>