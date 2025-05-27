<?php

try {
    // Obtener todas las compras
    $stmt = $pdo->query("
        SELECT c.id, c.fecha, c.total, c.codigo, p.nombre AS proveedor
        FROM compras c
        LEFT JOIN proveedores p ON c.proveedor_id = p.id
        ORDER BY c.fecha DESC
    ");
    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener detalles de materiales por compra
    $stmt = $pdo->query("
        SELECT dc.compra_id, m.nombre AS material, dc.cantidad, dc.precio_unitario, m.stock_minimo AS stock_minimo
        FROM detalles_compra dc
        INNER JOIN materiales m ON dc.material_id = m.id
    ");
    $detallesPorCompra = [];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $det) {
        $detallesPorCompra[$det['compra_id']][] = $det;
    }
} catch (PDOException $e) {
    die("Error al cargar compras: " . htmlspecialchars($e->getMessage()));
}
?>



<div id="content" class="container-fluid p-3">
    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-bag-check-fill me-2"></i> Historial de Compras
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar compras..." id="buscador">
            </div>
            <a href="index.php?vista=registrar_compras" class="btn btn-secondary mb-3"><i class="bi bi-plus"></i> Nueva
                Compra</a>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaRoles" class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th>Código</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Total</th>
                            <th>Material</th>
                            <th>Stock</th>
                            <th>Precio Unitario</th>
                            <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php foreach ($compras as $compra): ?>
                            <?php foreach ($detallesPorCompra[$compra['id']] ?? [] as $detalle): ?>
                                <tr>
                                    <td><?= $compra['id'] ?></td>
                                    <td><?= $compra['codigo'] ?></td>
                                    <td><?= $compra['fecha'] ?></td>
                                    <td><?= htmlspecialchars($compra['proveedor']) ?></td>
                                    <td>$<?= number_format($compra['total'], 2) ?></td>
                                    <td><?= htmlspecialchars($detalle['material']) ?></td>
                                    <td><?= $detalle['cantidad'] ?></td>
                                    <td>XAF <?= number_format($detalle['precio_unitario'], 2) ?></td>
                                    <td>
                                        <a href="index.php?vista=editar_compras&id=<?= $compra['id'] ?>"
                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="index.php?vista=editar_compras&id=<?= $compra['id'] ?>"
                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
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


<script>

    const buscador = document.getElementById('buscador');
    let paginaActual = 1;

    //cargar las funciones al cargarse la pagina completamente
    document.addEventListener('DOMContentLoaded', () => {
        cargarDatos();
        clickPaginacion()
        manejarEventosAjaxTbody(); // Necesario cuando cargamos html por ajax
        // buscar()

    });

    function manejarEventosAjaxTbody() {
        document.getElementById("tbody").addEventListener("click", function (e) {
            //eliminar un registro de la fila por ID            
            if (e.target.closest(".btn-eliminar")) {
                const id = e.target.closest(".btn-eliminar").dataset.id;
                eliminar(id);
            }


        });

    }
    async function eliminar(id) {
        if (!confirm("¿Estás seguro de eliminar esta compra?")) return;

        const formData = new FormData();
        formData.append("id", id);

        try {
            const res = await fetch('api/eliminar_compra.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (data.success) {
                alert('Compra eliminada correctamente.');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'No se pudo eliminar la compra.'));
            }
        } catch (err) {
            console.error('Error:', err);
            alert('Error de conexión al intentar eliminar.');
        }
    }

    async function cargarDatos(pagina = 1, termino = '') {
        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('termino', termino);
        try {
            const res = await fetch('api/listar_compras.php', {
                method: 'POST',
                body: formData
            })

            const data = await res.json();
            if (data.success) {
                document.getElementById('tbody').innerHTML = data.html;
                document.getElementById('paginacion').innerHTML = data.paginacion;
                document.getElementById('resumen-paginacion').textContent = data.resumen;
                paginaActual = pagina; // actualizar página actual
            } else {
                alert(data.message);
                console.log(data.message)
            }

        } catch (error) {
            alert('Error al cargar datos:', error);
            console.log(error)
        }

    }

    // Buscar
    function buscar() {
        buscador.addEventListener('input', async () => {
            paginaActual = 1;
            await cargarDatos(paginaActual, buscador.value.trim());
        });

    }
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