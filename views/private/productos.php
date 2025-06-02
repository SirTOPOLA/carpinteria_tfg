<?php


$stmt_total = $pdo->prepare("SELECT  * FROM productos  
ORDER BY nombre DESC;
");
$stmt_total->execute();
$productos = $stmt_total->fetchAll();

$rol = isset($_SESSION['usuario']['rol']) ? strtolower(trim($_SESSION['usuario']['rol'])) : '';

?>


<div id="content" class="container-fliud">
    <!-- Card con tabla de roles -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-kanban-fill me-2"></i> Gestión de productos
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar productos..." id="buscador">
            </div>


            <?php if (in_array($rol, ['administrador', 'diseñador'])):   ?>
                <a href="index.php?vista=registrar_productos" class="btn btn-secondary">
                    <i class="bi bi-plus"></i> Nuevo Producto
                </a>
            <?php endif; ?>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-image me-1"></i>Imagen</th>
                            <th><i class="bi bi-box-seam me-1"></i>Nombre</th>
                            <th><i class="bi bi-card-text me-1"></i>Descripción</th>
                            <th><i class="bi bi-boxes me-1"></i>Stock</th>
                            <th><i class="bi bi-currency-dollar me-1"></i>Precio</th>

  <?php if (in_array($rol, ['administrador', 'diseñador'])):     ?>
                       
                                <th class="text-center"><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                            <?php endif; ?>
                        </tr>

                    </thead>
                    <tbody id="tbody">
                        <?php if (!empty($productos)): ?>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td><?= $producto['id'] ?></td>


                                    <td class="text-center">
                                        <?php if (!empty($producto['imagen']) && file_exists("api/" . $producto['imagen'])): ?>
                                            <img src="api/<?= $producto['imagen'] ?>" class="img-thumbnail img-modal-trigger"
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
                                                 <?php if (in_array($rol, ['administrador', 'diseñador'])):     ?>
                                            <a href="index.php?vista=editar_productos&id=<?= $producto['id'] ?>"
                                                class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                             <?php endif; ?>
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
        <div class="card-footer row py-2 d-flex justify-content-between">
            <div id="resumen-paginacion" class="col-12 col-md-4 text-muted small  text-center "></div>
            <!-- Controles de paginación -->
            <div id="paginacion" class="col-12 col-md-7  d-flex justify-content-center "></div>
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
    const buscador = document.getElementById('buscador');
    let paginaActual = 1;

    //cargar las funciones al cargarse la pagina completamente
    document.addEventListener('DOMContentLoaded', () => {
        cargarDatos();
        clickPaginacion()
        manejarEventosAjaxTbody(); // Necesario cuando cargamos html por ajax
        // buscar()
        abrirModalImagen()

    });

    // Abrir modal al hacer clic en la imagen
    function abrirModalImagen() {
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        const previewImage = document.getElementById('previewImage');

        document.querySelectorAll('.img-modal-trigger').forEach(img => {
            img.addEventListener('click', () => {
                const src = img.getAttribute('data-src');
                previewImage.src = src;
                modal.show();
            });
        });
    }




    function manejarEventosAjaxTbody() {
        document.getElementById("tbody").addEventListener("click", function (e) {
            //eliminar un registro de la fila por ID            
            if (e.target.closest(".btn-eliminar")) {
                const id = e.target.closest(".btn-eliminar").dataset.id;
                eliminar(id);
            }
            //cambiar estado 
            /*  if (e.target.closest(".toggle-estado")) {
                 const btn = e.target.closest(".toggle-estado");
                 const id = btn.dataset.id;
                 const estado = parseInt(btn.dataset.estado);
                 cambiarEstado(id, estado);
             } */


        });

    }

    async function eliminar(id) {
        if (confirm('¿Seguro que quieres eliminar este producto? id: ' + id)) {
            try {
                const response = await fetch(`api/eliminar_productos.php?id=${id}`, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Error al eliminar el producto.');
                }
            } catch (error) {
                alert('Error en la petición.');
            }
        }
    }

    /* 
        async function cambiarEstado(id, estado) {
            if (confirm(`¿Estás seguro de ${estado ? 'desactivar' : 'activar'} este producto?`)) {
                try {
                    const res = await fetch('api/toggle_estado_producto.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id, nuevo_estado: estado ? 0 : 1 })
                    });
    
                    const data = await res.json();
                    if (data.success) {
                        location.reload(); // refresca para ver el cambio
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    alert('Error en la petición.');
                }
            }
    
        }
    
     */
    async function cargarDatos(pagina = 1, termino = '') {
        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('termino', termino);
        try {
            const res = await fetch('api/listar_productos.php', {
                method: 'POST',
                body: formData
            })
            const data = await res.json();
            console.log(data.message)
            if (data.success) {
                document.getElementById('tbody').innerHTML = data.html;
                document.getElementById('paginacion').innerHTML = data.paginacion;
                document.getElementById('resumen-paginacion').textContent = data.resumen;
                paginaActual = pagina; // actualizar página actual
            } else {
                alert(data.message);
                console.log()
            }

        } catch (error) {
            alert('Error al cargar datos:', error);
            console.log(error)
        }

    }

    // Buscar
    /* function buscar() {
        buscador.addEventListener('input', async () => {
            paginaActual = 1;
            await cargarDatos(paginaActual, buscador.value.trim());
        });

    } */
    // Manejar clics en paginación
    function clickPaginacion() {
        document.getElementById('paginacion').addEventListener('click', async e => {
            const btn = e.target.closest('.pagina-link');
            if (btn) {
                e.preventDefault();
                const nuevaPagina = parseInt(btn.dataset.pagina);
                if (!isNaN(nuevaPagina)) {
                    paginaActual = nuevaPagina;
                    await cargarDatos(paginaActual, buscador.value.trim());
                }
            }
        });

    }


</script>