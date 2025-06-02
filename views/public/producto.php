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

<main class="min-vh-100 d-flex flex-column bg-body-tertiary position-relative">

    <!-- Canvas de partículas (opcional, si lo usas) -->
    <div id="tsparticles" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 0;"></div>

    <!-- Hero Seccional -->
    <section class="py-5 text-center text-white position-relative overflow-hidden"
        style="background: linear-gradient(to right, #1f2937cc, #4b5563cc), url('<?= htmlspecialchars($heroRuta) ?>') center/cover no-repeat;">
        <div class="container py-3">
            <h1 class="fw-bold"><i class="bi bi-grid-fill"></i> Catálogo de Productos y Servicios</h1>
            <p class="lead">Explora nuestra colección de muebles artesanales únicos</p>
        </div>
    </section>

    <div class="container py-4" style="z-index: 10; position: relative;">
        <!-- Buscador en tiempo real -->
        <div class="mb-4">
            <input id="buscador" type="search" class="form-control form-control-lg rounded-pill shadow-sm"
                placeholder="Buscar productos o servicios...">
        </div>

        <div class="row g-4" id="catalogo">

            <!-- Productos -->
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="col-sm-6 col-md-4 col-lg-3 item-card" data-tipo="producto"
                        data-nombre="<?= htmlspecialchars(strtolower($producto['nombre'])) ?>"
                        data-descripcion="<?= htmlspecialchars(strtolower($producto['descripcion'])) ?>" data-aos="fade-up">
                        <div class="card shadow-sm border-0 rounded-4 h-100 hover-scale">
                            <?php if ($producto['imagen']): ?>
                                <img src="api/<?= htmlspecialchars($producto['imagen']) ?>" class="card-img-top rounded-top-4"
                                    style="aspect-ratio: 1/1; object-fit: cover;"
                                    alt="<?= htmlspecialchars($producto['nombre']) ?>">
                            <?php else: ?>
                                <img src="img/no-image.png" class="card-img-top rounded-top-4"
                                    style="aspect-ratio: 1/1; object-fit: cover;" alt="Sin imagen">
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column position-relative">
                                <h5 class="card-title text-primary-emphasis text-uppercase fw-bold mb-2"
                                    style="letter-spacing: 0.05em;">
                                    <i class="bi bi-box-seam me-2"></i><?= htmlspecialchars($producto['nombre']) ?>
                                </h5>

                                <p class="card-text small text-secondary p-2 rounded-3 position-relative"
                                    style="background: rgba(0,0,0,0.05); backdrop-filter: saturate(180%) blur(6px);">
                                    <?= htmlspecialchars(mb_strimwidth($producto['descripcion'], 0, 90, '...')) ?>
                                </p>

                                <div class="mt-auto pt-3 border-top d-flex flex-column gap-1">
                                    <p class="fw-bold text-success mb-0">
                                        <span class="me-1">FCFA</span><?= number_format($producto['precio_unitario'], 2) ?>
                                    </p>
                                    <p class="text-muted small mb-1">
                                        <i class="bi bi-box me-1"></i> Stock: <?= (int) $producto['stock'] ?>
                                    </p>

                                    <?php if ((int) $producto['stock'] <= 0): ?>
                                        <span class="badge bg-danger align-self-start">
                                            <i class="bi bi-exclamation-circle me-1"></i> Sin stock
                                        </span>
                                    <?php endif; ?>

                                    <button class="btn btn-primary btn-sm w-100 mt-2 rounded-pill btn-solicitar"
                                        data-nombre="<?= htmlspecialchars($producto['nombre']) ?>" data-tipo="Producto">
                                        <i class="bi bi-envelope me-1"></i> Solicitar Información
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-5">
                    <i class="bi bi-box-seam fs-1 mb-3 d-block"></i>
                    <h5 class="fw-semibold">No hay productos disponibles por el momento.</h5>
                </div>
            <?php endif; ?>

            <!-- separación -->
            <div class="w-100 my-5 text-center">
                <i class="bi bi-stars fs-3 text-primary-emphasis"></i>
            </div>

            <!-- Servicios -->
            <?php if (!empty($servicios)): ?>
                <?php foreach ($servicios as $servicio): ?>
                    <div class="col-sm-6 col-md-4 col-lg-3 item-card" data-tipo="servicio"
                        data-nombre="<?= htmlspecialchars(strtolower($servicio['nombre'])) ?>"
                        data-descripcion="<?= htmlspecialchars(strtolower($servicio['descripcion'])) ?>" data-aos="fade-up">
                        <div
                            class="card shadow-sm border-0 rounded-4 h-100 bg-white position-relative overflow-hidden hover-scale">
                            <div class="card-body d-flex flex-column p-4">
                                <h5 class="card-title text-uppercase fw-semibold text-primary-emphasis mb-2"
                                    style="letter-spacing: 0.03em;">
                                    <i class="bi bi-tools me-2 text-secondary"></i><?= htmlspecialchars($servicio['nombre']) ?>
                                </h5>

                                <div class="bg-body-secondary bg-opacity-75 p-2 rounded-3 mb-3 text-secondary small"
                                    style="backdrop-filter: blur(5px);">
                                    <?= htmlspecialchars(mb_strimwidth($servicio['descripcion'], 0, 90, '...')) ?>
                                </div>

                                <div class="mt-auto">
                                    <p class="fw-bold text-success mb-2">
                                        <span class="me-1">FCFA</span><?= number_format($servicio['precio_base'], 2) ?>
                                        <span class="text-muted small">/ <?= htmlspecialchars($servicio['unidad']) ?></span>
                                    </p>
                                    <button class="btn btn-primary btn-sm w-100 rounded-pill btn-solicitar"
                                        data-nombre="<?= htmlspecialchars($servicio['nombre']) ?>" data-tipo="Servicio">
                                        <i class="bi bi-envelope me-1"></i> Solicitar Información
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-5">
                    <i class="bi bi-tools fs-1 mb-3 d-block"></i>
                    <h5 class="fw-semibold">No hay servicios disponibles por el momento.</h5>
                </div>
            <?php endif; ?>

        </div>
    </div>

</main>

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