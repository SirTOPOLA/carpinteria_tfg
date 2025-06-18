<?php
try {
    // Obtener productos
    $stmt_productos = $pdo->prepare("SELECT * FROM productos  
        ORDER BY nombre ASC
    ");
    $stmt_productos->execute();
    $productos = $stmt_productos->fetchAll();

    // Obtener servicios activos
    $stmt_servicios = $pdo->prepare("
        SELECT * FROM servicios WHERE activo = 1 ORDER BY nombre ASC
    ");
    $stmt_servicios->execute();
    $servicios = $stmt_servicios->fetchAll();

} catch (PDOException $e) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Error al cargar datos: " . htmlspecialchars($e->getMessage()) . "</div></div>";
    exit;
}
?>

<!-- AOS Animaciones -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });
</script>

 
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --card-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            --card-shadow-hover: 0 16px 48px rgba(0, 0, 0, 0.15);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .hero-section {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, #e0e7ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .search-container {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }

        .search-input {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 20px 60px 20px 25px;
            font-size: 1.1rem;
            box-shadow: var(--card-shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .search-input:focus {
            outline: none;
            border-color: rgba(102, 126, 234, 0.5);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1), var(--card-shadow-hover);
            transform: translateY(-2px);
        }

        .search-icon {
            position: absolute;
            right: 25px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 1.3rem;
        }

        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            padding: 2rem 0;
        }

        .product-card, .service-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .product-card::before, .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .service-card::before {
            background: var(--secondary-gradient);
        }

        .product-card:hover, .service-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-shadow-hover);
        }

        .product-card:hover::before, .service-card:hover::before {
            opacity: 1;
        }

        .card-image {
            aspect-ratio: 1;
            object-fit: cover;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover .card-image {
            transform: scale(1.05);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #1e293b;
        }

        .card-description {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 1rem;
            border-radius: 12px;
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .price-tag {
            background: var(--success-gradient);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            display: inline-block;
            margin-bottom: 0.75rem;
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
        }

        .stock-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #6b7280;
        }

        .stock-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .stock-available {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .stock-unavailable {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .btn-request {
            background: var(--primary-gradient);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-request::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-request:hover::before {
            left: 100%;
        }

        .btn-request:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-request.service {
            background: var(--secondary-gradient);
        }

        .btn-request.service:hover {
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.4);
        }

        .section-divider {
            text-align: center;
            margin: 4rem 0;
            position: relative;
        }

        .section-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        }

        .section-divider .icon {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 1rem;
            border-radius: 50%;
            font-size: 2rem;
            color: var(--primary-color);
            box-shadow: var(--card-shadow);
            position: relative;
            z-index: 1;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .empty-state i {
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 1rem;
        }

        .filter-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .filter-tab {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-tab:hover, .filter-tab.active {
            background: rgba(255, 255, 255, 0.9);
            color: #1e293b;
            transform: translateY(-2px);
            box-shadow: var(--card-shadow);
        }

        .fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .service-icon {
            width: 60px;
            height: 60px;
            background: var(--secondary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
            box-shadow: 0 4px 12px rgba(240, 147, 251, 0.3);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .catalog-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .search-input {
                padding: 15px 50px 15px 20px;
                font-size: 1rem;
            }
        }
    </style>
 
    <main class="min-vh-100">
        <!-- Hero Section -->
        <section class="hero-section py-5 text-center text-white">
            <div class="container py-4">
                <h1 class="hero-title mb-4">
                    <i class="bi bi-grid-fill me-3"></i>
                    Catálogo Premium
                </h1>
                <p class="lead fs-4 mb-0" style="color: rgba(255, 255, 255, 0.9);">
                    Descubre nuestra colección exclusiva de muebles artesanales
                </p>
            </div>
        </section>

        <div class="container py-5">
            <!-- Búsqueda Avanzada -->
            <div class="search-container mb-5">
                <input id="buscador" type="search" class="search-input w-100" 
                       placeholder="Buscar productos y servicios...">
                <i class="bi bi-search search-icon"></i>
            </div>

            <!-- Filtros -->
            <div class="filter-tabs">
                <div class="filter-tab active" data-filter="all">
                    <i class="bi bi-grid me-2"></i>Todo
                </div>
                <div class="filter-tab" data-filter="producto">
                    <i class="bi bi-box-seam me-2"></i>Productos
                </div>
                <div class="filter-tab" data-filter="servicio">
                    <i class="bi bi-tools me-2"></i>Servicios
                </div>
            </div>

            <!-- Grid de Catálogo -->
            <div class="catalog-grid" id="catalogo">
                <!-- Productos de ejemplo -->
                <div class="product-card item-card fade-in" data-tipo="producto" 
                     data-nombre="mesa artesanal" data-descripcion="mesa de madera maciza hecha a mano">
                    <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=400&fit=crop" 
                         class="card-image w-100" alt="Mesa Artesanal">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-box-seam me-2"></i>Mesa Artesanal Premium
                        </h5>
                        <div class="card-description">
                            Mesa de madera maciza hecha completamente a mano por artesanos expertos. Acabado natural que resalta la belleza de la madera.
                        </div>
                        <div class="price-tag">FCFA 125,000.00</div>
                        <div class="stock-info">
                            <i class="bi bi-box"></i>
                            <span>Stock: 8 unidades</span>
                            <div class="stock-badge stock-available">Disponible</div>
                        </div>
                        <button class="btn-request" data-nombre="Mesa Artesanal Premium" data-tipo="Producto">
                            <i class="bi bi-envelope me-2"></i>Solicitar Información
                        </button>
                    </div>
                </div>

                <div class="product-card item-card fade-in" data-tipo="producto" 
                     data-nombre="silla ergonómica" data-descripcion="silla de oficina con soporte lumbar">
                    <img src="https://images.unsplash.com/photo-1586400508517-db40bf8e1f80?w=400&h=400&fit=crop" 
                         class="card-image w-100" alt="Silla Ergonómica">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-box-seam me-2"></i>Silla Ergonómica Deluxe
                        </h5>
                        <div class="card-description">
                            Silla de oficina con diseño ergonómico, soporte lumbar ajustable y materiales de alta calidad para máximo confort.
                        </div>
                        <div class="price-tag">FCFA 85,500.00</div>
                        <div class="stock-info">
                            <i class="bi bi-box"></i>
                            <span>Stock: 0 unidades</span>
                            <div class="stock-badge stock-unavailable">Sin Stock</div>
                        </div>
                        <button class="btn-request" data-nombre="Silla Ergonómica Deluxe" data-tipo="Producto">
                            <i class="bi bi-envelope me-2"></i>Solicitar Información
                        </button>
                    </div>
                </div>

                <div class="product-card item-card fade-in" data-tipo="producto" 
                     data-nombre="estantería modular" data-descripcion="sistema de almacenamiento versátil">
                    <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=400&fit=crop" 
                         class="card-image w-100" alt="Estantería Modular">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-box-seam me-2"></i>Estantería Modular Flex
                        </h5>
                        <div class="card-description">
                            Sistema de estantería modular que se adapta a cualquier espacio. Fácil montaje y configuración personalizable.
                        </div>
                        <div class="price-tag">FCFA 67,200.00</div>
                        <div class="stock-info">
                            <i class="bi bi-box"></i>
                            <span>Stock: 15 unidades</span>
                            <div class="stock-badge stock-available">Disponible</div>
                        </div>
                        <button class="btn-request" data-nombre="Estantería Modular Flex" data-tipo="Producto">
                            <i class="bi bi-envelope me-2"></i>Solicitar Información
                        </button>
                    </div>
                </div>

                <!-- Separador -->
                <div class="section-divider w-100">
                    <div class="icon">
                        <i class="bi bi-stars"></i>
                    </div>
                </div>

                <!-- Servicios de ejemplo -->
                <div class="service-card item-card fade-in" data-tipo="servicio" 
                     data-nombre="restauración muebles" data-descripcion="servicio profesional de restauración">
                    <div class="card-body">
                        <div class="service-icon">
                            <i class="bi bi-tools"></i>
                        </div>
                        <h5 class="card-title">Restauración de Muebles</h5>
                        <div class="card-description">
                            Servicio profesional de restauración que devuelve la vida a tus muebles antiguos con técnicas tradicionales y materiales de calidad.
                        </div>
                        <div class="price-tag">FCFA 25,000.00 <span style="font-size: 0.8em; opacity: 0.8;">/ pieza</span></div>
                        <button class="btn-request service" data-nombre="Restauración de Muebles" data-tipo="Servicio">
                            <i class="bi bi-envelope me-2"></i>Solicitar Información
                        </button>
                    </div>
                </div>

                <div class="service-card item-card fade-in" data-tipo="servicio" 
                     data-nombre="diseño personalizado" data-descripcion="creación de muebles únicos a medida">
                    <div class="card-body">
                        <div class="service-icon">
                            <i class="bi bi-palette"></i>
                        </div>
                        <h5 class="card-title">Diseño Personalizado</h5>
                        <div class="card-description">
                            Creamos muebles únicos diseñados específicamente para tu espacio y necesidades. Desde el concepto hasta la entrega final.
                        </div>
                        <div class="price-tag">FCFA 50,000.00 <span style="font-size: 0.8em; opacity: 0.8;">/ proyecto</span></div>
                        <button class="btn-request service" data-nombre="Diseño Personalizado" data-tipo="Servicio">
                            <i class="bi bi-envelope me-2"></i>Solicitar Información
                        </button>
                    </div>
                </div>

                <div class="service-card item-card fade-in" data-tipo="servicio" 
                     data-nombre="consultoría decoración" data-descripcion="asesoramiento profesional en decoración">
                    <div class="card-body">
                        <div class="service-icon">
                            <i class="bi bi-house-heart"></i>
                        </div>
                        <h5 class="card-title">Consultoría en Decoración</h5>
                        <div class="card-description">
                            Asesoramiento profesional para transformar tu espacio. Incluye análisis del ambiente, propuestas de diseño y guía de implementación.
                        </div>
                        <div class="price-tag">FCFA 35,000.00 <span style="font-size: 0.8em; opacity: 0.8;">/ consulta</span></div>
                        <button class="btn-request service" data-nombre="Consultoría en Decoración" data-tipo="Servicio">
                            <i class="bi bi-envelope me-2"></i>Solicitar Información
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Funcionalidad de búsqueda en tiempo real
        const buscador = document.getElementById('buscador');
        const itemCards = document.querySelectorAll('.item-card');
        const filterTabs = document.querySelectorAll('.filter-tab');
        let activeFilter = 'all';

        // Búsqueda
        buscador.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            filterItems(searchTerm, activeFilter);
        });

        // Filtros
        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remover active de todos los tabs
                filterTabs.forEach(t => t.classList.remove('active'));
                // Agregar active al tab clickeado
                this.classList.add('active');
                
                activeFilter = this.dataset.filter;
                const searchTerm = buscador.value.toLowerCase().trim();
                filterItems(searchTerm, activeFilter);
            });
        });

        function filterItems(searchTerm, filter) {
            itemCards.forEach(card => {
                const nombre = card.dataset.nombre || '';
                const descripcion = card.dataset.descripcion || '';
                const tipo = card.dataset.tipo || '';
                
                const matchesSearch = !searchTerm || 
                    nombre.includes(searchTerm) || 
                    descripcion.includes(searchTerm);
                
                const matchesFilter = filter === 'all' || tipo === filter;
                
                if (matchesSearch && matchesFilter) {
                    card.style.display = 'block';
                    card.classList.add('fade-in');
                } else {
                    card.style.display = 'none';
                    card.classList.remove('fade-in');
                }
            });
        }

        // Funcionalidad de botones de solicitar información
        document.querySelectorAll('.btn-request').forEach(button => {
            button.addEventListener('click', function() {
                const nombre = this.dataset.nombre;
                const tipo = this.dataset.tipo;
                
                // Aquí puedes agregar la lógica para manejar la solicitud
                alert(`Solicitud de información para ${tipo}: ${nombre}`);
                
                // Efecto visual
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });

        // Animación de entrada progresiva
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationDelay = Math.random() * 0.3 + 's';
                    entry.target.classList.add('fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observar elementos para animación
        document.querySelectorAll('.item-card').forEach(card => {
            observer.observe(card);
        });
    </script>
 

<!-- Modal Contacto -->
<div class="modal fade" id="modalContacto" tabindex="-1" aria-labelledby="modalContactoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content rounded-4 shadow border-0">
      <!-- Header del modal -->
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title" id="modalContactoLabel">Solicitar Información</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- Formulario -->
      <form id="formContacto" novalidate>
        <div class="modal-body">
          <p class="mb-3" id="contactoProductoServicio"></p>

          <!-- Nombre -->
          <div class="mb-3">
            <label for="nombre" class="form-label d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3z"/>
                <path fill-rule="evenodd" d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
              </svg>
              Nombre <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
            <div class="invalid-feedback">Por favor ingresa tu nombre.</div>
          </div>

          <!-- Código -->
          <div class="mb-3">
            <label for="codigo" class="form-label d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-upc-scan" viewBox="0 0 16 16">
                <path d="M1.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 1 0v-13a.5.5 0 0 0-.5-.5zm12 0a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-1 0v-13a.5.5 0 0 1 .5-.5zM6 1h1v14H6V1zm2 0h1v14H8V1z"/>
              </svg>
              Código <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" id="codigo" name="codigo" required>
            <div class="invalid-feedback">El código es obligatorio.</div>
          </div>

          <!-- Teléfono -->
          <div class="mb-3">
            <label for="telefono" class="form-label d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.674 1.63a1 1 0 0 1 1.04-.268l2.8 1.05a1 1 0 0 1 .55.49l1.25 2.498a1 1 0 0 1-.114 1.072L6.55 7.805a11.292 11.292 0 0 0 4.615 4.615l1.334-1.288a1 1 0 0 1 1.074-.113l2.497 1.25a1 1 0 0 1 .49.551l1.05 2.798a1 1 0 0 1-.264 1.04l-1.61 1.908c-.5.594-1.31.81-2.05.507-1.52-.63-3.263-2.4-4.8-4.8-1.55-2.43-2.243-4.54-1.628-5.716z"/>
              </svg>
              Teléfono
            </label>
            <input type="tel" class="form-control" id="telefono" name="telefono" pattern="^\+?\d{7,15}$" placeholder="+521234567890">
            <div class="invalid-feedback">Número inválido. Solo números y opcional "+".</div>
          </div>

          <!-- Dirección -->
          <div class="mb-3">
            <label for="direccion" class="form-label d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zM8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/>
              </svg>
              Dirección
            </label>
            <input type="text" class="form-control" id="direccion" name="direccion">
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label for="email" class="form-label d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.761 4.143A2 2 0 0 0 2 14h12a2 2 0 0 0 2-1.027l-6.761-4.143L8 9.586l-1.239-.757zM16 4.697l-5.803 3.546L16 11.801V4.697z"/>
              </svg>
              Correo electrónico
            </label>
            <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@correo.com">
            <div class="invalid-feedback">Correo inválido.</div>
          </div>

          <!-- Descripción -->
          <div class="mb-3">
            <label for="descripcion" class="form-label d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-chat-text-fill" viewBox="0 0 16 16">
                <path d="M8 2a6 6 0 1 0 4.546 10.88L16 16l-1.114-3.39A6 6 0 0 0 8 2zM5 6.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5z"/>
              </svg>
              Descripción <span class="text-danger">*</span>
            </label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
            <div class="invalid-feedback">La descripción es obligatoria.</div>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Estilos extra para hover -->
<style>
    .hover-scale:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
</style>

<script>
    // Filtrado en tiempo real
    const buscador = document.getElementById('buscador');
    const catalogo = document.getElementById('catalogo');
    const items = catalogo.querySelectorAll('.item-card');

    buscador.addEventListener('input', () => {
        const texto = buscador.value.toLowerCase().trim();

        items.forEach(item => {
            const nombre = item.getAttribute('data-nombre');
            const descripcion = item.getAttribute('data-descripcion');
            if (nombre.includes(texto) || descripcion.includes(texto)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Modal contacto dinámico
    const modal = new bootstrap.Modal(document.getElementById('modalContacto'));
    const contactoProductoServicio = document.getElementById('contactoProductoServicio');
    const formContacto = document.getElementById('formContacto');

    // Cuando se hace click en cualquier botón "Solicitar Información"
    document.querySelectorAll('.btn-solicitar').forEach(button => {
        button.addEventListener('click', () => {
            const nombre = button.getAttribute('data-nombre');
            const tipo = button.getAttribute('data-tipo');
            contactoProductoServicio.textContent = `Has solicitado información sobre el ${tipo.toLowerCase()}: "${nombre}". Por favor completa el formulario para que podamos contactarte.`;

            // Resetear form
            formContacto.reset();
            formContacto.classList.remove('was-validated');

            modal.show();
        });
    });

    // Validación y envío formulario (simulado)
    formContacto.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!formContacto.checkValidity()) {
            e.stopPropagation();
            formContacto.classList.add('was-validated');
            return;
        }

        // Aquí puedes poner tu lógica para enviar los datos via AJAX o formulario tradicional
        alert('Gracias por tu solicitud. Nos pondremos en contacto pronto.');

        modal.hide();
    });
    formContacto.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!formContacto.checkValidity()) {
            e.stopPropagation();
            formContacto.classList.add('was-validated');
            return;
        }

        // Enviar datos por fetch al backend PHP
        const formData = new FormData(formContacto);

        fetch('api/solicitud_catalogo.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.errores) {
                    // Mostrar errores (puedes adaptar esta parte)
                    alert('Errores: ' + JSON.stringify(data.errores));
                    return;
                }
                alert(data.mensaje);

                // Redirigir a WhatsApp para contacto inmediato
                if (data.whatsapp_url) {
                    window.open(data.whatsapp_url, '_blank');
                }

                modal.hide();
                formContacto.reset();
                formContacto.classList.remove('was-validated');
            })
            .catch(err => {
                alert('Error en el envío, intenta más tarde.');
                console.error(err);
            });
    });

</script>