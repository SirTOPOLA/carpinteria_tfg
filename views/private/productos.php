<?php


$stmt_total = $pdo->prepare("SELECT 
    p.*,  
    i.ruta_imagen AS imagen
FROM productos p 
LEFT JOIN imagenes_producto i ON p.id = i.producto_id
ORDER BY p.nombre DESC;
");
$stmt_total->execute();
$productos = $stmt_total->fetchAll();



?>


<div id="content" class="container-fluid py-4">
    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-kanban-fill me-2"></i> Gestión de productos
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar productos..." id="buscador-productos">
            </div>
            <a href="index.php?vista=registrar_productos" class="btn btn-secondary">
                <i class="bi bi-plus"></i> Nuevo Producto
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th>Imagen</th>
                            <th><i class="bi bi-card-heading me-1"></i>Nombre</th>
                            <th><i class="bi bi-chat-square-text me-1"></i>Descripción</th> 
                            <th><i class="bi bi-chat-square-text me-1"></i>stock</th> 
                            <th><i class="bi bi-currency-euro me-1"></i>Precio</th> 
                            <th class="text-center"><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>  
                        <?php if (!empty($productos)): ?>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td><?= $producto['id'] ?></td>
                                 

                                    <td class="text-center">
                                        <?php if (!empty($producto['imagen']) && file_exists("api/" . $producto['imagen'])): ?>
                                            <img src="api/<?= $producto['imagen'] ?>" 
                                                class="img-thumbnail img-modal-trigger"
                                                data-src="api/<?= $producto['imagen'] ?>"
                                                style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;">
                                        <?php else: ?>
                                            <span class="text-muted">Sin imagen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                                    <td><?= htmlspecialchars($producto['descripcion']) ?></td> 
                                    <td><?= htmlspecialchars($producto['stock']) ?></td> 
                                    <td>€<?= number_format($producto['precio_unitario'], 2) ?></td> 
                                    <td class="text-center">
                                        <a href="index.php?vista=editar_productos&id=<?= $producto['id'] ?>"
                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <!--
                                        <a href="eliminar_producto.php?id=<?= $producto['id'] ?>" 
                                            class="btn btn-sm btn-danger" title="Eliminar"
                                            onclick="return confirm('¿Está seguro de eliminar este producto?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No se encontraron productos.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para previsualizar imagen -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark">
      <div class="modal-body p-0 text-center">
        <img id="previewImage" src="" alt="Vista previa" class="img-fluid rounded">
      </div>
    </div>
  </div>
</div>

<script>
    // Abrir modal al hacer clic en la imagen
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        const previewImage = document.getElementById('previewImage');

        document.querySelectorAll('.img-modal-trigger').forEach(img => {
            img.addEventListener('click', () => {
                const src = img.getAttribute('data-src');
                previewImage.src = src;
                modal.show();
            });
        });
    });
</script>
