<?php


$stmt_total = $pdo->prepare("SELECT  *  FROM productos ");
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
                <input type="text" class="form-control" placeholder="Buscar productos.." id="buscador-productos">
            </div>
            <a href="index.php?vista=registrar_productos" class="btn btn-secondary">

                <i class="bi bi-plus"> </i>Nuevo Producto</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th>Imagen</th>
                            <th><i class="bi bi-card-heading me-1"></i>Nombre</th>
                            <th><i class="bi bi-flag-fill me-1"></i>Descripción</th>
    
                            <th><i class="bi bi-clock-history me-1"></i>Coste</th>
                            <th><i class="bi bi-calendar-event me-1"></i>Creado</th>
                     
                            <th class="text-center"><i class="bi bi-gear-fill me-1"></i>Acciones</th>
 
 
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($productos) ): ?>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?= $producto['id'] ?></td>
                                <td class="text-center">
                                    <?php if (!empty($producto['imagen']) && file_exists("../uploads/" . $producto['imagen'])): ?>
                                        <img src="../uploads/<?= $producto['imagen'] ?>" class="img-thumbnail img-modal-trigger"
                                            data-src="../uploads/<?= $producto['imagen'] ?>"
                                            style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;">
                                    <?php else: ?>
                                        <span class="text-muted">Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                                <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                                <td><?= htmlspecialchars($producto['categoria'] ?? 'Sin categoría') ?></td>
                                <td>€<?= number_format($producto['precio'], 2) ?></td>
                                <td><?= date("d/m/Y H:i", strtotime($producto['fecha_creacion'])) ?></td>
                                <td class="text-center">
                                    <a href="index.php?vista=editar_productos&id=<?= $producto['id'] ?>"
                                        class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <!--   <a href="eliminar_producto.php?id=<?= $producto['id'] ?>" class="btn btn-sm btn-danger"
                                        title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este producto?');">
                                        <i class="bi bi-trash"></i>
                                    </a> -->
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const triggers = document.querySelectorAll(".img-modal-trigger");
        const modalImg = document.getElementById("imagenAmpliada");

        triggers.forEach(img => {
            img.addEventListener("click", function () {
                const src = this.getAttribute("data-src");
                modalImg.src = src;
                const modal = new bootstrap.Modal(document.getElementById('modalImagen'));
                modal.show();
            });
        });
    });
</script>