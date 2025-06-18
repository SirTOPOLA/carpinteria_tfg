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

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: var(--shadow);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
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
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
 
   

    <!-- Main Content -->
    <div id="content" class="container ">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card fade-in">
                    <div class="card-body p-4">
                        <h2 class="text-primary mb-2">¡Bienvenido de vuelta, Juan!</h2>
                        <p class="text-muted mb-0">Aquí tienes un resumen de tus proyectos y pedidos actuales.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card fade-in">
                    <div class="stats-number">3</div>
                    <div class="stats-label">Pedidos Activos</div>
                    <div class="position-relative">
                        <i class="fas fa-tools fa-2x mt-2 opacity-50"></i>
                        <span class="notification-dot">2</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card warning fade-in">
                    <div class="stats-number">1</div>
                    <div class="stats-label">En Producción</div>
                    <i class="fas fa-hammer fa-2x mt-2 opacity-50"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card success fade-in">
                    <div class="stats-number">5</div>
                    <div class="stats-label">Completados</div>
                    <i class="fas fa-check-circle fa-2x mt-2 opacity-50"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card info fade-in">
                    <div class="stats-number">$2,450</div>
                    <div class="stats-label">Total Invertido</div>
                    <i class="fas fa-dollar-sign fa-2x mt-2 opacity-50"></i>
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
                    <div class="card-body p-0">
                        <!-- Pedido 1 -->
                        <div class="pedido-card card m-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="text-primary mb-1">Mesa de Comedor Rústica</h6>
                                        <small class="text-muted">#PED-2024-001 • Solicitado: 15 Jun 2024</small>
                                    </div>
                                    <span class="estado-badge estado-proceso">En Proceso</span>
                                </div>
                                
                                <div class="progress-container">
                                    <div class="d-flex justify-content-between mb-2">
                                        <small class="text-muted">Progreso del proyecto</small>
                                        <small class="text-primary fw-bold">65%</small>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 65%"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Fecha entrega:</small>
                                        <div class="fw-bold">25 Jun 2024</div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Precio:</small>
                                        <div class="fw-bold text-success">$850.00</div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button class="btn btn-outline-primary btn-sm me-2" onclick="verDetalles(1)">
                                        <i class="fas fa-eye me-1"></i>Ver Detalles
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="verAvances(1)">
                                        <i class="fas fa-images me-1"></i>Ver Avances
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Pedido 2 -->
                        <div class="pedido-card card m-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="text-primary mb-1">Estantería Personalizada</h6>
                                        <small class="text-muted">#PED-2024-002 • Solicitado: 18 Jun 2024</small>
                                    </div>
                                    <span class="estado-badge estado-pendiente">Pendiente</span>
                                </div>
                                
                                <div class="progress-container">
                                    <div class="d-flex justify-content-between mb-2">
                                        <small class="text-muted">Progreso del proyecto</small>
                                        <small class="text-primary fw-bold">15%</small>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 15%"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Fecha entrega:</small>
                                        <div class="fw-bold">02 Jul 2024</div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Precio:</small>
                                        <div class="fw-bold text-success">$1,200.00</div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button class="btn btn-outline-primary btn-sm me-2" onclick="verDetalles(2)">
                                        <i class="fas fa-eye me-1"></i>Ver Detalles
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="verAvances(2)">
                                        <i class="fas fa-images me-1"></i>Ver Avances
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Pedido 3 -->
                        <div class="pedido-card card m-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="text-primary mb-1">Cama King Size con Cabecera</h6>
                                        <small class="text-muted">#PED-2024-003 • Solicitado: 20 Jun 2024</small>
                                    </div>
                                    <span class="estado-badge estado-completado">Completado</span>
                                </div>
                                
                                <div class="progress-container">
                                    <div class="d-flex justify-content-between mb-2">
                                        <small class="text-muted">Progreso del proyecto</small>
                                        <small class="text-success fw-bold">100%</small>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: 100%"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Fecha entrega:</small>
                                        <div class="fw-bold">22 Jun 2024</div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Precio:</small>
                                        <div class="fw-bold text-success">$1,400.00</div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button class="btn btn-success btn-sm me-2">
                                        <i class="fas fa-truck me-1"></i>Listo para Entrega
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="verAvances(3)">
                                        <i class="fas fa-images me-1"></i>Ver Resultado
                                    </button>
                                </div>
                            </div>
                        </div>
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
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Función para ver detalles del pedido
        function verDetalles(pedidoId) {
            // Aquí iría la lógica para mostrar detalles del pedido
            alert(`Ver detalles del pedido #${pedidoId}`);
        }

        // Función para ver avances del proyecto
        function verAvances(pedidoId) {
            const modal = new bootstrap.Modal(document.getElementById('avancesModal'));
            const content = document.getElementById('avancesContent');
            
            // Simular contenido de avances
            const avancesData = {
                1: {
                    titulo: "Mesa de Comedor Rústica",
                    avances: [
                        { fecha: "15 Jun 2024", descripcion: "Inicio del proyecto - Preparación de materiales", porcentaje: 10, imagen: "https://via.placeholder.com/300x200/8B4513/ffffff?text=Materiales" },
                        { fecha: "17 Jun 2024", descripcion: "Corte y preparación de la madera", porcentaje: 35, imagen: "https://via.placeholder.com/300x200/D2691E/ffffff?text=Corte+Madera" },
                        { fecha: "19 Jun 2024", descripcion: "Ensamblaje de la estructura principal", porcentaje: 65, imagen: "https://via.placeholder.com/300x200/F4A460/ffffff?text=Ensamblaje" }
                    ]
                },
                2: {
                    titulo: "Estantería Personalizada",
                    avances: [
                        { fecha: "18 Jun 2024", descripcion: "Diseño y planificación inicial", porcentaje: 15, imagen: "https://via.placeholder.com/300x200/8B4513/ffffff?text=Diseño" }
                    ]
                },
                3: {
                    titulo: "Cama King Size con Cabecera",
                    avances: [
                        { fecha: "20 Jun 2024", descripcion: "Proyecto completado - Acabados finales", porcentaje: 100, imagen: "https://via.placeholder.com/300x200/28a745/ffffff?text=Completado" }
                    ]
                }
            };

            const proyecto = avancesData[pedidoId];
            
            content.innerHTML = `
                <h6 class="text-primary mb-4">${proyecto.titulo}</h6>
                <div class="timeline">
                    ${proyecto.avances.map(avance => `
                        <div class="timeline-item">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="${avance.imagen}" class="img-fluid rounded" alt="Avance">
                                </div>
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-between mb-2">
                                        <small class="text-muted">${avance.fecha}</small>
                                        <span class="badge bg-primary">${avance.porcentaje}%</span>
                                    </div>
                                    <p class="mb-2">${avance.descripcion}</p>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" style="width: ${avance.porcentaje}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
            
            modal.show();
        }

        // Animaciones al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            // Añadir efecto de fade-in escalonado
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
 