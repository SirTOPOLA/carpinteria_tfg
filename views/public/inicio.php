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

<main class="min-vh-100 d-flex flex-column bg-dark text-white">

  <!-- Hero Completo a Pantalla -->
  <section class="hero flex-grow-1 d-flex align-items-center justify-content-center text-center position-relative overflow-hidden">
    <div class="container">
      <h1 class="display-4 fw-bold text-uppercase mb-3">
        <i class="bi bi-tree-fill me-2"></i>Diseños únicos en madera
      </h1>
      <p class="lead mb-4">Transformamos tus ideas en muebles personalizados con calidad artesanal.</p>

      <div class="d-flex justify-content-center gap-3 flex-wrap mb-5">
        <a href="index.php?vista=producto" class="btn btn-warning btn-lg px-4 rounded-pill shadow-sm">
          <i class="bi bi-box-seam me-2"></i> Ver Catálogo
        </a>
        <a href="index.php?vista=contacto" class="btn btn-outline-light btn-lg px-4 rounded-pill">
          <i class="bi bi-whatsapp me-2"></i> Hacer un pedido
        </a>
      </div>

      <!-- Carrusel si hay imágenes -->
      <?php if (!empty($imagenes)): ?>
        <div id="heroCarousel" class="carousel slide w-100 mx-auto shadow rounded overflow-hidden" style="max-width: 900px;" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php foreach ($imagenes as $index => $img): ?>
              <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <img src="api/<?= htmlspecialchars($img) ?>" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="api/<?= $index + 1 ?>">
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
  </section>

</main>
 
