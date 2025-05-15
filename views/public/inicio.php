<?php
try {
    $sql = "SELECT ruta_imagen 
            FROM imagenes_producto 
            ORDER BY RAND() 
            LIMIT 1"; // selecciona 6 imágenes aleatorias

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $imagenes = $stmt->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    $imagenes = [];
}

?>

<!-- Hero Section mejorado -->
<section class="hero bg-dark text-white py-5 position-relative overflow-hidden">
  <div class="container text-center">
    <h1 class="display-4 fw-bold">Diseños únicos en madera</h1>
    <p class="lead mb-4">Creamos muebles a medida con pasión y detalle artesanal.</p>
    <a href="index.php?vista=producto" class="btn btn-warning btn-lg px-4 me-2">
      <i class="bi bi-box-seam"></i> Ver Catálogo
    </a>
    <a href="index.php?vista=contacto" class="btn btn-outline-light btn-lg px-4">
      <i class="bi bi-pencil-square"></i> Hacer un pedido
    </a>
  </div>
</section>

<!-- Galería aleatoria de imágenes desde BD -->
<section class="container my-5">
  <h2 class="text-center mb-4"><i class="bi bi-images"></i> Nuestro trabajo</h2>
  <div class="row g-3">
    <?php if (!empty($imagenes)): ?>
      <?php foreach ($imagenes as $ruta): ?>
        <div class="col-6 col-md-4 col-lg-2">
          <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <img src="api/<?= htmlspecialchars($ruta) ?>"
                 class="img-fluid object-fit-cover h-100"
                 style="aspect-ratio: 1/1;"
                 alt="Mueble artesanal">
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-warning text-center">
          <i class="bi bi-exclamation-circle"></i> No hay imágenes disponibles en este momento.
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
