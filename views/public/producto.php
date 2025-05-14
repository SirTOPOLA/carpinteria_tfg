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

<div class="container my-5 min-vh-90 d-flex flex-column">
    <h2 class="text-center mb-4"><i class="bi bi-box-seam"></i> Catálogo de Productos</h2>

    <div class="row g-4 flex-grow-1">
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $producto): ?>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-lg rounded-4 border-0">
                        <?php if ($producto['imagen']): ?>
                            <img src="api/<?= htmlspecialchars($producto['imagen']) ?>"
                                class="card-img-top img-fluid rounded-top-4" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                        <?php else: ?>
                            <img src="img/no-image.png" class="card-img-top img-fluid rounded-top-4" alt="Sin imagen">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary-emphasis"><?= htmlspecialchars($producto['nombre']) ?></h5>
                            <p class="card-text small text-secondary-emphasis">
                                <?= htmlspecialchars(mb_strimwidth($producto['descripcion'], 0, 100, '...')) ?></p>
                            <div class="mt-auto">
                                <p class="fw-bold text-success-emphasis mb-2">
                                    <i class="bi bi-currency-dollar"></i> <?= number_format($producto['precio_unitario'], 2) ?>
                                </p>
                                <p class="text-muted small"><i class="bi bi-box"></i> Stock: <?= (int) $producto['stock'] ?></p>
                                <a href="producto.php?id=<?= $producto['id'] ?>"
                                    class="btn btn-outline-primary btn-sm rounded-pill w-100">
                                    <i class="bi bi-eye"></i> Ver detalles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 d-flex align-items-center justify-content-center" style="min-height: 50vh;">
                <div class="alert alert-warning text-center w-100">
                    <i class="bi bi-exclamation-triangle"></i> No hay productos disponibles.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>