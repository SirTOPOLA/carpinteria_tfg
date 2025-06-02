<?php
// Conexión
require_once "config/conexion.php";

// Verifica si ya hay usuarios
$sql = "SELECT COUNT(*) FROM usuarios";
$stmt = $pdo->query($sql);
$hay_usuarios = $stmt->fetchColumn() > 0;

/* if ($hay_usuarios) {
    // Redirigir al login si ya existe al menos un usuario
    header("Location: login.php");
    exit;
} */
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro Inicial</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .form-label i {
      color: #0d6efd;
      margin-right: 6px;
    }
    .form-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #0d6efd;
    }
    .form-control {
      border-radius: 0.5rem;
    }
    .btn-primary {
      border-radius: 0.5rem;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card p-4">
          <h2 class="form-title mb-4 text-center"><i class="bi bi-person-plus-fill"></i> Registro Inicial de Usuario</h2>
          <form action="guardar_usuario_inicial.php" method="POST" novalidate>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label"><i class="bi bi-person-fill"></i> Nombre completo</label>
                <input type="text" name="nombre" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="bi bi-envelope-fill"></i> Correo electrónico</label>
                <input type="email" name="correo" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="bi bi-lock-fill"></i> Contraseña</label>
                <input type="password" name="contrasena" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="bi bi-lock-fill"></i> Confirmar contraseña</label>
                <input type="password" name="confirmar_contrasena" class="form-control" required>
              </div>
              <div class="col-12 mt-4">
                <button type="submit" class="btn btn-primary w-100">
                  <i class="bi bi-check-circle-fill"></i> Registrar usuario inicial
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>



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
                            <th><i class="bi bi-currency-dollar me-1"></i>subtotal</th>
                            <th><i class="bi bi-currency-dollar me-1"></i>Descuento</th>
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
                                    <th class="text-end">descuento</th>
                                    <th class="text-end">total</th>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-arrow-left"></i>
                    Cerrar</button>

            </div>
        </div>
    </div>
</div>

<!-- Modal de Pago de Factura -->
<div class="modal fade" id="modalPagoFactura" tabindex="-1" aria-labelledby="modalPagoFacturaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formRegistrarPago">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPagoFacturaLabel">Registrar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="factura_id" id="factura_id">
                    <div class="mb-2">
                        <label>Cliente:</label>
                        <input type="text" id="nombre_cliente" class="form-control" readonly>
                    </div>
                    <div class="mb-2">
                        <label>Total factura:</label>
                        <input type="text" id="total_factura" class="form-control" readonly>
                    </div>
                    <div class="mb-2">
                        <label>Saldo pendiente:</label>
                        <input type="text" id="saldo_pendiente" class="form-control" readonly>
                    </div>
                    <div class="mb-2">
                        <label>Monto a pagar:</label>
                        <input type="number" name="monto_pagado" id="monto_pagado" class="form-control" min="0.01"
                            step="0.01" required>
                    </div>
                    <div class="mb-2">
                        <label>Método de pago:</label>
                        <input type="text" name="metodo_pago" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Observaciones:</label>
                        <textarea name="observaciones" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar pago</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="modalPagoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-gradient bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-receipt"></i> Detalles del Pago</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary">Información del Pago</h6>
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
                        <p><strong>Factura ID:</strong> <span id="detalle-factura"></span></p>
                        <p><strong>Fecha de emisión:</strong> <span id="detalle-emision"></span></p>
                        <p><strong>Monto total:</strong> <span id="detalle-total"></span></p>
                        <p><strong>Saldo pendiente:</strong> <span id="detalle-saldo"></span></p>
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
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i
                        class="bi bi-x-circle"></i> Cerrar</button>
                <button type="button" class="btn btn-outline-primary" onclick="imprimirRecibo()"><i
                        class="bi bi-printer"></i> Imprimir recibo</button>
            </div>
        </div>
    </div>
</div>


