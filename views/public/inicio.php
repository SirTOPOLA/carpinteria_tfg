<?php
try {
  $sql = "SELECT ruta_imagen 
          FROM imagenes_producto 
          ORDER BY RAND() 
          LIMIT 6";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $imagenes = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
  $imagenes = [];
}
?>

<?php if (empty($usuario)): ?>
  <main class=" min-vh-100 d-flex flex-column position-relative">

    <section
      class="flex-grow-1 d-flex align-items-center justify-content-center text-center position-relative overflow-hidden"
      style="z-index: 1; background: #5af;">

      <!-- Canvas de partículas -->
      <div id="tsparticles" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 0;"></div>

      <div class="container position-relative text-white" style="z-index: 2;">
        <div class="p-5 rounded-4 shadow-lg" style="background-color: rgba(23, 70, 124, 0.7);">
          <h1 class="display-4 fw-bold text-uppercase mb-3">
            <i class="bi bi-tree-fill me-2 text-white"></i>
            <?= htmlspecialchars($nombre_empresa ?? 'Sistema de Carpintería') ?>
          </h1>
          <p class="lead mb-4">
            <?= htmlspecialchars($mision ?? 'Este sistema aún no ha sido configurado.') ?>
          </p>
          <a href="configuracion.php" class="btn btn-outline-warning btn-lg px-4 rounded-pill shadow-sm" role="button">
            <i class="bi bi-tools me-2"></i> Iniciar Configuración
          </a>
        </div>
      </div>
    </section>

  <?php else: ?>
    <main class=" min-vh-100 d-flex flex-column position-relative">
      <section
        class="flex-grow-1 d-flex align-items-center justify-content-center text-center position-relative overflow-hidden"
        style="background: linear-gradient(to right, rgba(31, 41, 55, 0.85), rgba(75, 85, 99, 0.85)), url('<?= htmlspecialchars($heroRuta) ?>') center/cover no-repeat;">

        <div class="container text-white">
          <h1 class="display-4 fw-bold text-uppercase mb-3">
            <i class="bi bi-tree-fill me-2 text-warning"></i>
            <?= htmlspecialchars($nombre_empresa ?? '') ?>
          </h1>
          <p class="lead mb-4"><?= htmlspecialchars($mision ?? '') ?></p>

          <div class="d-flex justify-content-center gap-3 flex-wrap mb-5">
            <a href="index.php?vista=producto" class="btn btn-warning btn-lg px-4 rounded-pill shadow-sm me-3"
              aria-label="Ver Catálogo">
              <i class="bi bi-box-seam me-2"></i> Ver Catálogo
            </a>
            <a href="index.php?vista=contacto" class="btn btn-outline-light btn-lg px-4 rounded-pill"
              aria-label="Hacer un pedido">
              <i class="bi bi-whatsapp me-2"></i> Hacer un pedido
            </a>
          </div>

          <?php if (!empty($imagenes)): ?>
            <div id="heroCarousel" class="carousel slide w-100 mx-auto shadow rounded overflow-hidden"
              style="max-width: 900px;" data-bs-ride="carousel">
              <div class="carousel-inner">
                <?php foreach ($imagenes as $index => $img): ?>
                  <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="api/<?= htmlspecialchars($img) ?>" class="d-block w-100"
                      style="height: 400px; object-fit: cover;" alt="Imagen <?= $index + 1 ?>">
                  </div>
                <?php endforeach; ?>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
              </button>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>
