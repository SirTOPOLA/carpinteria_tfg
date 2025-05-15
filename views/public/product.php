
<?php
try {


    $stmt_total = $pdo->prepare("SELECT 
p.*,  
i.ruta_imagen AS imagen
FROM productos p 
LEFT JOIN imagenes_producto i ON p.id = i.producto_id
ORDER BY p.nombre DESC;
");
    $stmt_total->execute();
    $productos = $stmt_total->fetchAll();


} catch (PDOException $e) {
    echo "<div class='container mt-5'>
            <div class='alert alert-danger'>
                Error al conectar a la base de datos: " . htmlspecialchars($e->getMessage()) . "
            </div>
          </div>";
}
?>

<!-- Hero simple -->
<section class="bg-dark py-5 text-white text-center">
  <div class="container">
    <h1 class="display-5 fw-bold">Catálogo de Productos</h1>
    <p class="lead">Explora nuestra colección de muebles artesanales únicos</p>
  </div>
</section>

<!-- Filtros -->
<section class="container py-4 min-vh-90">
  <form class="row justify-content-center mb-4" method="get">
    <div class="col-md-6 col-lg-4">
      <select name="categoria" class="form-select" onchange="this.form.submit()">
        <option value="0">Todas las categorías</option>
        
      </select>
    </div>
  </form>

  <!-- Productos -->
  <div class="row g-4">
    <?php if (count($productos) > 0): ?>
      <?php foreach ($productos as $producto): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="card h-100 shadow-sm border-0 rounded-4">
            <img src="api/<?= htmlspecialchars($producto['imagen']) ?>" 
                 class="card-img-top object-fit-cover" 
                 style="aspect-ratio: 1/1;" 
                 alt="<?= htmlspecialchars($producto['nombre']) ?>">
            <div class="card-body d-flex flex-column">
              <h6 class="fw-bold"><?= htmlspecialchars($producto['nombre']) ?></h6>
              <div class="mt-auto">
                <span class="badge bg-warning text-dark fs-6">S/ <?= number_format($producto['precio_unitario'], 2) ?></span>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-info text-center">
          <i class="bi bi-info-circle"></i> No se encontraron productos en esta categoría.
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
