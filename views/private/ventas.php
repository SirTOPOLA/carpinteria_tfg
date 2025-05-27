<div id="content" class="container-fluid">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white"><i class="bi bi-receipt me-2"></i>Listado de Ventas</h4>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="buscador" placeholder="Buscar venta...">
            </div>
            <a href="index.php?vista=registrar_ventas" class="btn btn-secondary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Nueva venta
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle table-custom mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-person-fill me-1"></i>Cliente</th>
                            <th><i class="bi bi-calendar3 me-1"></i>Fecha</th>
                            <th><i class="bi bi-credit-card me-1"></i>Método de Pago</th>
                            <th><i class="bi bi-currency-dollar me-1"></i>Total</th>
                            <th class="text-center"><i class="bi bi-eye me-1"></i>Detalles</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyVentas">
                        <!-- Contenido dinámico por fetch -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer row py-2 d-flex justify-content-between">
            <div id="resumenVentas" class="col-12 col-md-4 text-muted small text-center"></div>
            <div id="paginacionVentas" class="col-12 col-md-7 d-flex justify-content-center"></div>
        </div>
    </div>
</div>


<!-- Modal Detalles de Venta -->
<div class="modal fade" id="modalDetallesVenta" tabindex="-1" aria-labelledby="tituloModalVenta" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header d-print-none">
        <h5 class="modal-title" id="tituloModalVenta">Factura de Venta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="factura-container">
        <div class="factura px-4 py-3" style="max-width: 800px; margin: auto; background: white;">
          <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
            <div>
              <img id="factura-logo" src="" alt="Logo Empresa" style="height: 80px;">
            </div>
            <div class="text-end">
              <h4 class="mb-0" id="empresa-nombre">Empresa S.A.C.</h4>
              <small id="empresa-direccion">Dirección de la empresa</small><br>
              <small id="empresa-contacto">Teléfono | Email</small>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-sm-6">
              <h6>Datos del Cliente</h6>
              <p id="cliente-nombre" class="mb-0 fw-semibold">Cliente</p>
            </div>
            <div class="col-sm-6 text-end">
              <h6>Factura #<span id="factura-numero">0000</span></h6>
              <p class="mb-0">Fecha: <span id="factura-fecha">__/__/____</span></p>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-sm">
              <thead class="table-light">
                <tr>
                  <th>Producto/Servicio</th>
                  <th>Tipo</th>
                  <th class="text-end">Cantidad</th>
                  <th class="text-end">Precio Unitario</th>
                  <th class="text-end">Subtotal</th>
                </tr>
              </thead>
              <tbody id="detalle-productos">
                <!-- Detalles cargados con JS -->
              </tbody>
            </table>
          </div>

          <div class="text-end pe-2">
            <h5>Total: $<span id="factura-total">0.00</span></h5>
          </div>
        </div>
      </div>
      <div class="modal-footer d-print-none">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-arrow-left"></i> Cerrar</button>
 
      </div>
    </div>
  </div>
</div>



<script>
    const buscador = document.getElementById('buscador');
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
                    inicializarBotonesDetalle(); // 👈 volver a asociar eventos
                }
            });
    }

    function manejarEventosAjaxTbody() {
        document.getElementById("tbodyVentas").addEventListener("click", function (e) {
            //eliminar un registro de la fila por ID            
            if (e.target.closest(".btn-eliminar")) {
                const id = e.target.closest(".btn-eliminar").dataset.id;
                eliminar(id);
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

    
    function inicializarBotonesDetalle() {
    document.querySelectorAll('.btn-toggle').forEach(btn => {
        btn.addEventListener('click', async () => {
            const ventaId = btn.dataset.id;
            const modal = new bootstrap.Modal(document.getElementById('modalDetallesVenta'));

            // Mostrar modal inmediatamente (luego se reemplaza contenido)
            modal.show();

            // Limpiar contenido anterior
            document.getElementById('detalle-productos').innerHTML = '';
            document.getElementById('factura-logo').src = '';
            document.getElementById('empresa-nombre').textContent = '';
            document.getElementById('empresa-direccion').textContent = '';
            document.getElementById('empresa-contacto').textContent = '';

            try {
                const formData = new FormData();
                formData.append('id', ventaId);

                const res = await fetch('api/detalles_venta.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                if (data.success) {
                    const { venta, detalles, config } = data;

                    // Datos de empresa
                    document.getElementById('empresa-nombre').textContent = config.nombre || '';
                    document.getElementById('empresa-direccion').textContent = config.direccion || '';
                    document.getElementById('empresa-contacto').textContent = `${config.telefono || ''} | ${config.email || ''}`;
                    if (config.logo) {
                        document.getElementById('factura-logo').src = 'api/' + config.logo;
                    }

                    // Datos de factura
                    document.getElementById('cliente-nombre').textContent = venta.cliente;
                    document.getElementById('factura-numero').textContent = venta.id;
                    document.getElementById('factura-fecha').textContent = new Date(venta.fecha).toLocaleDateString();
                    document.getElementById('factura-total').textContent = parseFloat(venta.total).toFixed(2);

                    // Detalles
                    const tbody = document.getElementById('detalle-productos');
                    for (let item of detalles) {
                        const subtotal = item.cantidad * item.precio_unitario;
                        const fila = `
                            <tr>
                                <td>${item.nombre}</td>
                                <td>${item.tipo}</td>
                                <td class="text-end">${item.cantidad}</td>
                                <td class="text-end">$${parseFloat(item.precio_unitario).toFixed(2)}</td>
                                <td class="text-end">$${subtotal.toFixed(2)}</td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', fila);
                    }
                } else {
                    document.getElementById('detalle-productos').innerHTML = `
                        <tr><td colspan="5" class="text-danger">${data.mensaje}</td></tr>
                    `;
                }

            } catch (err) {
                document.getElementById('detalle-productos').innerHTML = `
                    <tr><td colspan="5" class="text-danger">Error al cargar detalles de la venta.</td></tr>
                `;
            }
        });
    });
}



    document.addEventListener('DOMContentLoaded', () => {
        cargarVentas();

        buscador.addEventListener('keyup', function () {
            const termino = this.value.trim();
            cargarVentas(1, termino);
        });

        document.getElementById('paginacionVentas').addEventListener('click', function (e) {
            if (e.target.classList.contains('pagina-link')) {
                const pagina = e.target.dataset.pagina;
                const termino = buscador.value.trim();
                cargarVentas(pagina, termino);
            }
        });
    });
</script>