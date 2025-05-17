<?php

try {
    // Obtener productos
    $stmt_productos = $pdo->prepare("
        SELECT p.*, i.ruta_imagen AS imagen
        FROM productos p
        LEFT JOIN imagenes_producto i ON p.id = i.producto_id
        ORDER BY p.nombre ASC
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

<main class="min-vh-100 d-flex flex-column bg-body-tertiary">
<!-- Canvas de partículas -->
<div id="tsparticles" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 0;"></div>

    <!-- Hero Seccional -->
    <section class="py-5 text-center text-white position-relative overflow-hidden"
        style="background: linear-gradient(to right, #1f2937cc, #4b5563cc), url('<?= htmlspecialchars($heroRuta) ?>') center/cover no-repeat;">
        <div class="container py-3">
            <h1 class="fw-bold"><i class="bi bi-grid-fill"></i> Catálogo de Productos y Servicios</h1>
            <p class="lead">Explora nuestra colección de muebles artesanales únicos</p>
        </div>
    </section>

    <div class="container ">
        <div class="row g-4">

            <!-- Productos -->
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <?php if ($producto['imagen']): ?>
                                <img src="api/<?= htmlspecialchars($producto['imagen']) ?>" class="card-img-top rounded-top-4"
                                    style="aspect-ratio: 1/1; object-fit: cover;" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                            <?php else: ?>
                                <img src="img/no-image.png" class="card-img-top rounded-top-4"
                                    style="aspect-ratio: 1/1; object-fit: cover;" alt="Sin imagen">
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column position-relative">
                                <h5 class="card-title text-primary-emphasis text-uppercase fw-bold mb-2" style="letter-spacing: 0.05em;">
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

                                    <button class="btn btn-outline-primary btn-sm w-100 mt-2 rounded-pill" data-bs-toggle="modal"
                                        data-bs-target="#vistaRapidaModal"
                                        data-info='<?= json_encode($producto, JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>'>
                                        <i class="bi bi-eye me-1"></i> Vista rápida
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

            <!-- Servicios -->
            <?php if (!empty($servicios)): ?>
                <?php foreach ($servicios as $servicio): ?>
                    <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up">
                        <div class="card shadow-sm border-0 rounded-4 h-100 bg-white position-relative overflow-hidden">
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
                                    <a href="servicio.php?id=<?= $servicio['id'] ?>"
                                        class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                                        <i class="bi bi-eye me-1"></i> Ver servicio
                                    </a>
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

    <!-- Modal Vista Rápida -->
    <div class="modal fade" id="vistaRapidaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header text-white rounded-top-4"
                    style="background: linear-gradient(to right, #1f2937, #4b5563);">
                    <h5 class="modal-title text-uppercase fw-bold" id="vistaRapidaTitle" style="letter-spacing: 0.05em;">
                        <!-- Título dinámico -->
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <img id="vistaRapidaImagen" src="" alt="Imagen producto"
                            class="img-fluid rounded-3 shadow-sm"
                            style="max-height: 300px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="col-md-6 d-flex flex-column justify-content-center">
                        <p id="vistaRapidaDescripcion" class="text-secondary bg-light p-3 rounded-3"
                            style="backdrop-filter: saturate(180%) blur(6px); font-size: 0.95rem;">
                            <!-- Descripción dinámica -->
                        </p>
                        <p class="fw-bold text-success fs-5 mt-3 mb-1" id="vistaRapidaPrecio">
                            <!-- Precio dinámico -->
                        </p>
                        <p class="text-muted small mb-0" id="vistaRapidaStock">
                            <!-- Stock dinámico -->
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-primary rounded-pill w-100"
                        data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

</main>

<script>
    const modal = document.getElementById('vistaRapidaModal');
    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const data = JSON.parse(button.getAttribute('data-info'));

        document.getElementById('vistaRapidaTitle').textContent = data.nombre;
        document.getElementById('vistaRapidaDescripcion').textContent = data.descripcion ?? '';
        document.getElementById('vistaRapidaPrecio').innerHTML = ' <span class="me-1">FCFA</span>' + parseFloat(data.precio_unitario).toFixed(2);
        document.getElementById('vistaRapidaStock').textContent = "Stock: " + data.stock;
        document.getElementById('vistaRapidaImagen').src = data.imagen ? 'api/' + data.imagen : 'img/no-image.png';
    });
</script>