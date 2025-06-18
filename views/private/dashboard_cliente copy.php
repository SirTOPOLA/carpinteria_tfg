<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}


$cliente = $_SESSION['usuario']; // suponiendo que el cliente está logueado
$cliente_id = $cliente['id']; // suponiendo que el cliente está logueado
// Total de pedidos
$stmt = $pdo->prepare("SELECT COUNT(*) FROM pedidos WHERE cliente_id = ?");
$stmt->execute([$cliente_id]);
$total_pedidos = $stmt->fetchColumn();

// Pedidos en producción
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM pedidos 
    WHERE cliente_id = ? AND estado_id IN (
        SELECT id FROM estados WHERE entidad = 'pedido' AND nombre LIKE '%producción%'
    )
");
$stmt->execute([$cliente_id]);
$en_produccion = $stmt->fetchColumn();

// Facturas pendientes
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM facturas f
    JOIN ventas v ON f.venta_id = v.id
    WHERE v.cliente_id = ? AND f.saldo_pendiente > 0
");
$stmt->execute([$cliente_id]);
$facturas_pendientes = $stmt->fetchColumn();

// Últimos avances
$stmt = $pdo->prepare("
    SELECT ap.descripcion, ap.imagen, ap.fecha
    FROM avances_produccion ap
    JOIN producciones pr ON ap.produccion_id = pr.id
    JOIN pedidos p ON pr.solicitud_id = p.id
    WHERE p.cliente_id = ?
    ORDER BY ap.fecha DESC
    LIMIT 3
");
$stmt->execute([$cliente_id]);
$avances = $stmt->fetchAll();
?>
<style>
  :root {
    --primary-color: #8B4513;
    --secondary-color: #D2691E;
    --accent-color: #F4A460;
    --success-color: #28a745;
    --warning-color: #ffc107;
    --danger-color: #dc3545;
    --dark-color: #2C3E50;
    --light-bg: #F8F9FA;
    --shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  }

  * {
    font-family: 'Inter', sans-serif;
  }

  body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
  }



  .main-container {
    padding: 2rem 0;
  }

  .card {
    border: none;
    border-radius: 15px;
    box-shadow: var(--shadow);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.2);
  }

  .card-header {
    border-bottom: none;
    border-radius: 15px 15px 0 0 !important;
    padding: 1.5rem;
  }

  .stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
    padding: 2rem 1rem;
  }

  .stats-card.warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  }

  .stats-card.success {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
  }

  .stats-card.info {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
  }

  .stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
  }

  .stats-label {
    font-size: 0.9rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .pedido-card {
    border-left: 4px solid var(--primary-color);
    margin-bottom: 1rem;
    transition: all 0.3s ease;
  }

  .pedido-card:hover {
    border-left-color: var(--secondary-color);
    box-shadow: var(--shadow);
  }

  .estado-badge {
    font-size: 0.75rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
  }

  .estado-pendiente {
    background: #fff3cd;
    color: #856404;
  }

  .estado-proceso {
    background: #cce5ff;
    color: #0066cc;
  }

  .estado-completado {
    background: #d4edda;
    color: #155724;
  }

  .estado-entregado {
    background: #e2e3e5;
    color: #383d41;
  }

  .progress-container {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1rem;
    margin: 1rem 0;
  }

  .progress {
    height: 8px;
    border-radius: 10px;
    background: #e9ecef;
  }

  .progress-bar {
    border-radius: 10px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
  }

  .timeline {
    position: relative;
    padding-left: 2rem;
  }

  .timeline::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
  }

  .timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding-left: 2rem;
  }

  .timeline-item::before {
    content: '';
    position: absolute;
    left: -0.5rem;
    top: 0.5rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    background: var(--primary-color);
    border: 3px solid white;
    box-shadow: var(--shadow-sm);
  }

  .btn-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border: none;
    border-radius: 25px;
    padding: 0.75rem 2rem;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
    background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
  }

  .fade-in {
    animation: fadeIn 0.6s ease-in;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(20px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .image-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
  }

  .gallery-item {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: transform 0.3s ease;
    cursor: pointer;
  }

  .gallery-item:hover {
    transform: scale(1.05);
  }

  .gallery-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
  }

  .notification-dot {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 20px;
    height: 20px;
    background: var(--danger-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.7rem;
    font-weight: bold;
  }

  @media (max-width: 768px) {
    .main-container {
      padding: 1rem 0;
    }

    .stats-number {
      font-size: 2rem;
    }

    .card {
      margin-bottom: 1rem;
    }
  }
</style>



<div id="content" class="container-fluid">
  <!-- Welcome Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card fade-in">
        <div class="card-body p-4">
          <h2 class="text-primary mb-2">¡Bienvenido de vuelta, <?= htmlspecialchars($cliente['nombre']) ?>!</h2>
          <p class="text-muted mb-0">Aquí tienes un resumen de tus proyectos y pedidos actuales.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="row g-3" id="resumenPedidos">
    <div class="col-lg-3 col-md-6 mb-3">
      <div class="card">
        <div class="card-body">
          <h6>Pedidos Activos</h6>
          <div id="pedidosActivos">0</div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
      <div class="card">
        <div class="card-body">
          <h6>En Producción</h6>
          <div id="enProduccion">0</div>
        </div>
      </div>
    </div>
     
    <div class="col-lg-3 col-md-6 mb-3">
      <div class="card">
        <div class="card-body">
          <h6>Completados</h6>
          <div id="completados">0</div>
        </div>
      </div>
    </div>


  </div>

  <div class="row">
    <!-- Pedidos Actuales -->
    <div class="col-lg-8 mb-4">
      <div class="card fade-in">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Mis Pedidos Actuales</h5>
        </div>
        <div id="listaPedidos" class="card-body p-0">



        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
      <!-- Notificaciones -->
      <div class="card mb-4 fade-in">
        <div class="card-header bg-warning text-dark">
          <h6 class="mb-0"><i class="fas fa-bell me-2"></i>Notificaciones Recientes</h6>
        </div>
        <div class="card-body p-3">
          <div class="timeline">
            <div class="timeline-item">
              <div class="d-flex justify-content-between">
                <small class="text-muted">Hace 2 horas</small>
                <span class="badge bg-success">Nuevo</span>
              </div>
              <div class="fw-bold text-success">Mesa de Comedor completada</div>
              <small class="text-muted">Tu mesa está lista para entrega</small>
            </div>
            <div class="timeline-item">
              <div class="d-flex justify-content-between">
                <small class="text-muted">Ayer</small>
              </div>
              <div class="fw-bold text-info">Avance de Estantería</div>
              <small class="text-muted">Se han subido nuevas fotos del progreso</small>
            </div>
            <div class="timeline-item">
              <div class="d-flex justify-content-between">
                <small class="text-muted">2 días atrás</small>
              </div>
              <div class="fw-bold text-primary">Nuevo presupuesto</div>
              <small class="text-muted">Presupuesto para armario disponible</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Contacto Rápido -->
      <div class="card fade-in">
        <div class="card-header bg-info text-white">
          <h6 class="mb-0"><i class="fas fa-phone me-2"></i>Contacto Rápido</h6>
        </div>
        <div class="card-body">
          <div class="text-center mb-3">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
              style="width: 60px; height: 60px;">
              <i class="fas fa-user-tie fa-2x text-primary"></i>
            </div>
            <h6 class="mt-2 mb-0">Carlos Martínez</h6>
            <small class="text-muted">Maestro Carpintero</small>
          </div>

          <div class="d-grid gap-2">
            <button class="btn btn-primary">
              <i class="fas fa-phone me-2"></i>Llamar
            </button>
            <button class="btn btn-outline-primary">
              <i class="fas fa-envelope me-2"></i>Enviar Email
            </button>
            <button class="btn btn-outline-success">
              <i class="fab fa-whatsapp me-2"></i>WhatsApp
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Ver Avances -->
<div class="modal fade" id="avancesModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-images me-2"></i>Avances del Proyecto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="avancesContent">
        <!-- Contenido dinámico -->
      </div>
    </div>
  </div>
</div>


<!-- Modal Detalles Pedido -->
<div class="modal fade" id="modalDetallesPedido" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Detalle del Pedido</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="detallePedidoContenido">
        <!-- Detalles se cargan por JS -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
 document.addEventListener("DOMContentLoaded", () => {
  function renderTarjetasResumen(pedidos) {
    const activos = pedidos.length;
    const produccion = pedidos.filter(p => p.estado.toLowerCase() === "en proceso").length;
    const completados = pedidos.filter(p => p.avance >= 100).length;

    document.getElementById("pedidosActivos").textContent = activos;
    document.getElementById("enProduccion").textContent = produccion;
    document.getElementById("completados").textContent = completados;
  }

  function verDetalles(idPedido) {
    fetch(`api/get_pedido_detalle.php?id=${idPedido}`)
      .then(res => res.json())
      .then(data => {
        const html = `
          <h6 class="mb-3 text-primary">Proyecto: ${data.proyecto}</h6>
          <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item"><strong>Fecha solicitud:</strong> ${data.fecha_solicitud}</li>
            <li class="list-group-item"><strong>Fecha entrega:</strong> ${data.fecha_entrega}</li>
            <li class="list-group-item"><strong>Piezas:</strong> ${data.piezas}</li>
            <li class="list-group-item"><strong>Servicio:</strong> ${data.servicio ?? 'No especificado'}</li>
            <li class="list-group-item"><strong>Precio Obra:</strong> CFA ${parseFloat(data.precio_obra || 0).toLocaleString('fr-FR')}</li>
            <li class="list-group-item"><strong>Adelanto:</strong> CFA ${parseFloat(data.adelanto || 0).toLocaleString('fr-FR')}</li>
            <li class="list-group-item"><strong>Estado:</strong> ${data.estado}</li>
          </ul>
          <p><strong>Descripción:</strong></p>
          <p>${data.descripcion || '<em>Sin descripción</em>'}</p>
        `;
        document.getElementById("detallePedidoContenido").innerHTML = html;
        new bootstrap.Modal(document.getElementById("modalDetallesPedido")).show();
      });
  }

  function renderPedidos(pedidos) {
    const container = document.getElementById("listaPedidos");
    container.innerHTML = "";

    pedidos.forEach(p => {
      const badgeClass = {
        "Pendiente": "bg-warning",
        "En Proceso": "bg-primary",
        "Completado": "bg-success"
      }[p.estado] || "bg-secondary";

      const isCompletado = p.avance >= 100;

      container.innerHTML += `
        <div class="pedido-card card m-3">
          <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
              <div>
                <h6 class="text-primary mb-0">${p.proyecto}</h6>
                <small class="text-muted">Solicitado: ${p.fecha_solicitud}</small>
              </div>
              <span class="badge ${badgeClass}">${p.estado}</span>
            </div>
            <div class="mb-2">
              <div class="d-flex justify-content-between">
                <small class="text-muted">Progreso</small>
                <small class="fw-bold">${p.avance}%</small>
              </div>
              <div class="progress">
                <div class="progress-bar ${isCompletado ? 'bg-success' : ''}" style="width: ${p.avance}%"></div>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-6"><small class="text-muted">Entrega:</small><div>${p.fecha_entrega}</div></div>
              <div class="col-6"><small class="text-muted">Total:</small><div class="text-success fw-bold">CFA ${parseFloat(p.estimacion_total).toLocaleString('fr-FR')}</div></div>
            </div>
            <div>
              <button class="btn btn-sm btn-outline-primary" onclick="verDetalles(${p.id})">
                <i class="fas fa-eye me-1"></i> Ver Detalles
              </button>
              <button class="btn btn-sm btn-outline-secondary" onclick="verAvances(${p.id})">
                <i class="fas fa-images me-1"></i> Ver Avances
              </button>
            </div>
          </div>
        </div>
      `;
    });
  }

  // Cargar datos del dashboard
  fetch("api/dashboard_cliente_data.php")
    .then(res => res.json())
    .then(data => {
      renderPedidos(data.pedidos);
      renderTarjetasResumen(data.pedidos);
    });

  // Efecto de fade-in
  const fadeElements = document.querySelectorAll(".fade-in");
  fadeElements.forEach((element, index) => {
    element.style.animationDelay = `${index * 0.1}s`;
    element.classList.add("animated", "fadeIn");
  });

  function verAvances(idPedido) {
  fetch(`api/get_avances_pedido.php?id=${idPedido}`)
    .then(res => res.json())
    .then(data => {
      let contenido = "";

      if (data.length === 0) {
        contenido = `<div class="alert alert-warning">Este pedido aún no tiene avances registrados.</div>`;
      } else {
        data.forEach(a => {
          contenido += `
            <div class="card mb-3">
              <div class="row g-0">
                <div class="col-md-4">
                  <img src="${a.imagen}" class="img-fluid rounded-start w-100 h-100 object-fit-cover" alt="avance">
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h6 class="card-title">Avance: ${a.porcentaje}%</h6>
                    <p class="card-text">${a.descripcion}</p>
                    <p class="card-text"><small class="text-muted">Fecha: ${a.fecha}</small></p>
                    <div class="progress">
                      <div class="progress-bar bg-success" style="width: ${a.porcentaje}%"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `;
        });
      }

      document.getElementById("contenidoAvances").innerHTML = contenido;
      new bootstrap.Modal(document.getElementById("modalAvances")).show();
    });
}



});

  // Animaciones al cargar la página
  document.addEventListener('DOMContentLoaded', function () {
    // Añadir efecto de fade-in escalonado
    const fadeElements = document.querySelectorAll('.fade-in');
    fadeElements.forEach((element, index) => {
      element.style.animationDelay = `${index * 0.1}s`;
    });
  });
</script>