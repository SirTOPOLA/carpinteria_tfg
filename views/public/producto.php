<?php
try {
    // Obtener productos
    $stmt_productos = $pdo->prepare("SELECT * FROM productos ORDER BY nombre ASC");
    $stmt_productos->execute();
    $productos = $stmt_productos->fetchAll();

    // Obtener servicios activos
    $stmt_servicios = $pdo->prepare("SELECT * FROM servicios WHERE activo = 1 ORDER BY nombre ASC");
    $stmt_servicios->execute();
    $servicios = $stmt_servicios->fetchAll();

    // Obtener pedidos recientes de clientes satisfechos (ficticio/demo)
    $stmt_pedidos = $pdo->prepare("SELECT c.nombre AS nombre_cliente, p.proyecto AS producto, prod.fecha_fin AS fecha FROM pedidos p
                                            LEFT JOIN clientes c ON c.id = p.cliente_id
                                            LEFT JOIN producciones prod ON p.id = prod.solicitud_id
                                            WHERE p.estado_id = (SELECT id FROM estados WHERE nombre = 'entregado' AND entidad = 'pedido')
                                            ORDER BY fecha DESC LIMIT 6");
    $stmt_pedidos->execute();
    $pedidos = $stmt_pedidos->fetchAll();

} catch (PDOException $e) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Error al cargar datos: " . htmlspecialchars($e->getMessage()) . "</div></div>";
    exit;
}
?>


<style>
    :root {
        --wood-primary: #2c3e50;
        /* Azul petróleo profundo */
        --wood-secondary: #7f8c8d;
        /* Gris medio elegante */
        --wood-accent: #c49b66;
        /* Marrón claro suave, tono madera refinada */
        --wood-light: #fdfaf6;
        /* Crema claro, casi blanco cálido */
        --wood-dark: #1a252f;
        /* Azul muy oscuro para contrastes */
        --gold-accent: #bfa77a;
        /* Oro viejo, elegante y sutil */
        --cream: #f5f0e6;
        /* Crema suave para fondos */
        --charcoal: #ecf0f1;
        /* Gris claro profesional */
    }




    .hero-section {
        background:
            linear-gradient(to right, #111827cc, #1f2937cc), url('<?= htmlspecialchars($heroRuta) ?>') center/cover no-repeat;
        position: relative;
        min-height: 60vh;
        display: flex;
        align-items: center;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="%23D2691E" opacity="0.1"/><circle cx="20" cy="20" r="1" fill="%23CD853F" opacity="0.1"/><circle cx="80" cy="30" r="1.5" fill="%23DEB887" opacity="0.1"/></svg>');
        animation: float 20s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-20px) rotate(180deg);
        }
    }

    .hero-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 3.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        position: relative;
    }

    .hero-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background: var(--gold-accent);
        border-radius: 2px;
    }

    .search-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 25px;
        padding: 8px;
        box-shadow: 0 10px 30px rgba(139, 69, 19, 0.15);
        border: 2px solid var(--wood-light);
    }

    .form-control {
        border: none;
        background: transparent;
        font-size: 1.1rem;
        padding: 15px 25px;
    }

    .form-control:focus {
        box-shadow: none;
        outline: none;
    }

    .catalog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
    }

    .product-card {
        background: linear-gradient(145deg, #ffffff 0%, #fefefe 100%);
        border-radius: 20px;
        overflow: hidden;
        box-shadow:
            0 10px 30px rgba(139, 69, 19, 0.1),
            0 1px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid rgba(139, 69, 19, 0.1);
        position: relative;
    }

    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--wood-primary), var(--gold-accent));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .product-card:hover::before {
        transform: scaleX(1);
    }

    .product-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow:
            0 20px 40px rgba(139, 69, 19, 0.2),
            0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .product-image {
        height: 250px;
        overflow: hidden;
        position: relative;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .product-image::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50px;
        background: linear-gradient(transparent, rgba(255, 255, 255, 0.8));
    }

    .product-title {
        font-family: 'Playfair Display', serif;
        font-weight: 600;
        color: var(--wood-primary);
        font-size: 1.4rem;
        margin-bottom: 0.5rem;
    }

    .product-description {
        color: #666;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    .price-tag {
        background: linear-gradient(135deg, var(--gold-accent), #DAA520);
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1.1rem;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }

    .stock-badge {
        font-size: 0.85rem;
        padding: 6px 12px;
        border-radius: 15px;
        font-weight: 500;
    }

    .btn-consultar {
        background: linear-gradient(135deg, var(--wood-primary), var(--wood-secondary));
        border: none;
        color: white;
        padding: 12px 24px;
        font-weight: 500;
        border-radius: 25px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.9rem;
    }

    .btn-consultar:hover {
        background: linear-gradient(135deg, var(--wood-secondary), var(--wood-primary));
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(139, 69, 19, 0.3);
        color: white;
    }

    .service-card {
        background: linear-gradient(145deg, var(--wood-light) 0%, rgb(223, 223, 223) 100%);
        border: 2px solid var(--wood-accent);
    }

    .service-card .product-title {
        color: var(--wood-dark);
    }

    .service-icon {
        width: 60px;
        height: 60px;
        background: var(--wood-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .section-divider {
        height: 4px;
        background: linear-gradient(90deg, transparent, var(--wood-primary), var(--gold-accent), var(--wood-primary), transparent);
        border: none;
        margin: 4rem 0;
        border-radius: 2px;
    }

    .testimonials-section {
        background: linear-gradient(135deg, rgba(8, 49, 65, 0.05) 0%, rgba(210, 105, 30, 0.29) 100%);
        border-radius: 30px;
        padding: 3rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .testimonials-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M10,50 Q30,10 50,50 T90,50" stroke="%23CD853F" stroke-width="0.5" fill="none" opacity="0.1"/></svg>');
        animation: rotate 30s linear infinite;
    }

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .testimonial-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        border: 1px solid rgba(139, 69, 19, 0.1);
        transition: transform 0.3s ease;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: var(--wood-primary);
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
    }

    .section-title::after {
        content: '✦';
        display: block;
        color: var(--gold-accent);
        font-size: 1.5rem;
        margin-top: 0.5rem;
    }

    .quote-icon {
        width: 40px;
        height: 40px;
        background: var(--wood-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .catalog-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }

    /* modal para zoom */
    .modal {
    display: none; /* Oculto por defecto */
    position: fixed; /* Permanece en su lugar */
    z-index: 9999; /* Z-index alto para que esté encima de todo */
    padding-top: 50px; /* Ubicación de la caja */
    left: 0;
    top: 0;
    width: 100%; /* Ancho completo */
    height: 100%; /* Alto completo */
    overflow: auto; /* Habilitar scroll si es necesario */
    background-color: rgba(58, 56, 56, 0.2); /* Fondo negro con opacidad */
    animation: fadeIn 0.3s forwards; /* Animación de entrada */
}

/* Contenido del modal (imagen) */
.modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    object-fit: contain; /* Asegura que la imagen se ajuste bien */
    max-height: 90vh; /* Para que la imagen no sea más grande que la ventana visible */
    animation: zoomIn 0.3s forwards; /* Animación de zoom para la imagen */
}

/* Botón de cerrar */
.close-button {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
    cursor: pointer;
}

.close-button:hover,
.close-button:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

/* Animaciones */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes zoomIn {
    from {
        transform: scale(0.8);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

/* Opcional: Cursor de puntero en las imágenes para indicar que son clickeables */
.zoomable-image {
    cursor: pointer;
}
</style>

<main class="min-vh-100 d-flex flex-column">
    <!-- Hero Section -->
    <section class="hero-section text-white text-center position-relative">
        <div class="container position-relative z-3">
            <h1 class="hero-title mb-4">
                <i class="bi bi-hammer"></i> Catálogo Exclusivo
            </h1>
            <p class="lead fs-4 mb-0">Descubre artesanía, calidad y diseño a tu medida</p>
            <p class="fs-6 opacity-75">Cada pieza cuenta una historia de tradición y maestría</p>
        </div>
    </section>

    <!-- Content Section -->
    <div class="container py-5">
        <!-- Search Bar -->
        <div class="search-container mb-5" data-aos="fade-up">
            <input id="buscador" type="search" class="form-control form-control-lg"
                placeholder="🔍 Buscar productos o servicios artesanales...">
        </div>

        <!-- Catalog Grid -->
        <div class="catalog-grid" id="catalogo"> <?php foreach ($productos as $producto): ?>
                <div class="item-card" data-tipo="producto"
                    data-nombre="<?= htmlspecialchars(strtolower($producto['nombre'])) ?>"
                    data-descripcion="<?= htmlspecialchars(strtolower($producto['descripcion'])) ?>" data-aos="zoom-in">

                    <div class="product-card">
                        <div class="product-image">
                            <img class="zoomable-image"
                                src="<?= $producto['imagen'] ? 'api/' . htmlspecialchars($producto['imagen']) : 'img/no-image.png' ?>"
                                data-full-image="<?= $producto['imagen'] ? 'api/' . htmlspecialchars($producto['imagen']) : 'img/no-image.png' ?>"
                                alt="<?= htmlspecialchars($producto['nombre']) ?>">
                        </div>

                        <div class="p-4 d-flex flex-column h-100">
                            <h5 class="product-title">
                                <?= htmlspecialchars($producto['nombre']) ?>
                            </h5>

                            <p class="product-description mb-3">
                                <?= htmlspecialchars(mb_strimwidth($producto['descripcion'], 0, 100, '...')) ?>
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="price-tag">
                                    FCFA <?= number_format($producto['precio_unitario'], 0, ',', '.') ?>
                                </span>
                                <span class="badge stock-badge <?= $producto['stock'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $producto['stock'] > 0 ? 'Disponible' : 'Sin stock' ?>
                                </span>
                            </div>

                            <button class="btn btn-consultar w-100 mt-auto btn-solicitar"
                                data-nombre="<?= htmlspecialchars($producto['nombre']) ?>" data-tipo="Producto">
                                <i class="bi bi-chat-dots me-1"></i> Consultar Pieza
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <div id="imageModal" class="modal">
            <span class="close-button">&times;</span>
            <img class="modal-content" id="img01">
        </div>
        <!-- Section Divider -->
        <hr class="section-divider">

        <!-- Services Section -->
        <div class="catalog-grid">
            <?php foreach ($servicios as $servicio): ?>
                <div class="item-card" data-tipo="servicio"
                    data-nombre="<?= htmlspecialchars(strtolower($servicio['nombre'])) ?>"
                    data-descripcion="<?= htmlspecialchars(strtolower($servicio['descripcion'])) ?>" data-aos="fade-up">

                    <div class="product-card service-card">
                        <div class="p-4 d-flex flex-column h-100">
                            <!-- Icono de herramienta -->
                            <div class="service-icon mb-2">
                                <i class="bi bi-tools"></i>
                            </div>

                            <!-- Título del servicio -->
                            <h5 class="product-title">
                                <?= htmlspecialchars($servicio['nombre']) ?>
                            </h5>

                            <!-- Descripción corta -->
                            <p class="product-description mb-3">
                                <?= htmlspecialchars(mb_strimwidth($servicio['descripcion'], 0, 100, '...')) ?>
                            </p>

                            <!-- Precio por unidad -->
                            <div class="mb-3">
                                <span class="price-tag">
                                    FCFA <?= number_format($servicio['precio_base'], 0, ',', '.') ?> /
                                    <?= htmlspecialchars($servicio['unidad']) ?>
                                </span>
                            </div>

                            <!-- Botón consultar -->
                            <button class="btn btn-consultar w-100 mt-auto btn-solicitar"
                                data-nombre="<?= htmlspecialchars($servicio['nombre']) ?>" data-tipo="Servicio">
                                <i class="bi bi-chat-dots me-1"></i> Consultar Servicio
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Testimonials Section -->
        <section class="testimonials-section mt-5" data-aos="fade-up">
            <h3 class="section-title">Clientes Satisfechos</h3>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($pedidos as $pedido): ?>
                    <div class="col" data-aos="fade-up">
                        <div class="testimonial-card p-4 h-100">
                            <div class="quote-icon mb-2">
                                <i class="bi bi-chat-quote fs-3 text-primary"></i>
                            </div>
                            <p class="mb-3">
                                Solicitud de <strong><?= htmlspecialchars($pedido['producto']) ?></strong>
                            </p>
                            <footer class="text-muted small">
                                <?= htmlspecialchars($pedido['nombre_cliente']) ?> el
                                <?= date("d/m/Y", strtotime($pedido['fecha'])) ?>
                            </footer>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</main>


<!-- Modal Contacto -->
<div class="modal fade" id="modalContacto" tabindex="-1" aria-labelledby="modalContactoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content rounded-4 shadow border-0">

            <!-- Header -->
            <div class="modal-header bg-primary text-white rounded-top-4 py-2 px-3">
                <h6 class="modal-title fw-semibold mb-0" id="modalContactoLabel">
                    <i class="bi bi-envelope-plus-fill me-2"></i>Solicitar Información
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>

            <!-- Formulario -->
            <form id="formContacto" novalidate>
                <div class="modal-body px-3 py-2">
                    <p id="contactoProductoServicio" class="mb-3 text-muted small"></p>

                    <!-- Nombre -->
                    <div class="mb-2">
                        <label for="nombre" class="form-label small">Nombre <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-primary border border-1 rounded-start-2">
                                <i class="bi bi-person-fill"></i>
                            </span>
                            <input type="text" class="form-control form-control-sm border border-1 rounded-end-2"
                                id="nombre" name="nombre" placeholder="Ej. Juan Pérez" required>
                        </div>
                        <div class="invalid-feedback">Por favor ingresa tu nombre.</div>
                    </div>

                    <!-- DIP -->
                    <div class="mb-2">
                        <label for="codigo" class="form-label small">DIP <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-primary border border-1 rounded-start-2">
                                <i class="bi bi-upc-scan"></i>
                            </span>
                            <input type="text" class="form-control form-control-sm border border-1 rounded-end-2"
                                id="codigo" name="codigo" placeholder="Ej. DIP-123456" required>
                        </div>
                        <div class="invalid-feedback">El DIP es obligatorio.</div>
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-2">
                        <label for="telefono" class="form-label small">Teléfono</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-primary border border-1 rounded-start-2">
                                <i class="bi bi-telephone-fill"></i>
                            </span>
                            <input type="tel" class="form-control form-control-sm border border-1 rounded-end-2"
                                id="telefono" name="telefono" pattern="^\+?\d{7,15}$" placeholder="Ej. +240123456789">
                        </div>
                        <div class="invalid-feedback">Número inválido. Solo números y opcional "+".</div>
                    </div>

                    <!-- Dirección -->
                    <div class="mb-2">
                        <label for="direccion" class="form-label small">Dirección</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-primary border border-1 rounded-start-2">
                                <i class="bi bi-geo-alt-fill"></i>
                            </span>
                            <input type="text" class="form-control form-control-sm border border-1 rounded-end-2"
                                id="direccion" name="direccion" placeholder="Ej. Calle Principal, N° 123">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-2">
                        <label for="email" class="form-label small">Correo electrónico <span
                                class="text-muted small">(opcional)</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-primary border border-1 rounded-start-2">
                                <i class="bi bi-envelope-fill"></i>
                            </span>
                            <input type="email" class="form-control form-control-sm border border-1 rounded-end-2"
                                id="email" name="email" placeholder="Ej. usuario@correo.com">
                        </div>
                        <div class="invalid-feedback">Correo inválido.</div>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-2">
                        <label for="descripcion" class="form-label small">Descripción <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control form-control-sm border border-1 rounded-2" id="descripcion"
                            name="descripcion" rows="2" placeholder="Describe tu consulta o solicitud"
                            required></textarea>
                        <div class="invalid-feedback">La descripción es obligatoria.</div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer py-2 px-3 border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-send-fill me-1"></i>Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
        easing: 'ease-out-cubic'
    });

    // Search functionality
    document.getElementById('buscador').addEventListener('input', function (e) {
        const searchTerm = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.item-card');

        items.forEach(item => {
            const nombre = item.dataset.nombre || '';
            const descripcion = item.dataset.descripcion || '';
            const text = item.textContent.toLowerCase();

            if (nombre.includes(searchTerm) ||
                descripcion.includes(searchTerm) ||
                text.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Smooth hover effects
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });

        card.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
</script>

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
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('img01');
    const closeButton = document.querySelector('.close-button');
    const zoomableImages = document.querySelectorAll('.zoomable-image');

    // Abre el modal cuando se hace clic en una imagen
    zoomableImages.forEach(img => {
        img.addEventListener('click', function() {
            modal.style.display = 'block';
            modalImg.src = this.dataset.fullImage; // Usa el atributo data-full-image
        });
    });

    // Cierra el modal cuando se hace clic en la 'x'
    closeButton.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Cierra el modal cuando se hace clic fuera de la imagen
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Cierra el modal con la tecla ESC
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            modal.style.display = 'none';
        }
    });
});
</script>
