<?php
$stmt = $pdo->prepare("SELECT * FROM servicios");
$stmt->execute();
$servicios = $stmt->fetchAll();

?>


<div id="content" class="container-fliud">
    <!-- Card con tabla de roles -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-person-lock me-2"></i> Gestión de Roles de Usuario
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar servicio..." id="buscador-roles">
            </div>
            <a href="index.php?vista=registrar_servicios" class="btn btn-secondary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Nuevo servicio
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaRoles" class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-box-seam me-1"></i>Nombre</th>
                            <th><i class="bi bi-grid-1x2 me-1"></i>Unidad</th>
                            <th><i class="bi bi-currency-dollar me-1"></i>Precio</th>
                            <th class="text-center"><i class="bi bi-check-circle me-1"></i>Activo</th>
                            <th class="text-center"><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php if (!empty($servicios)): ?>
                            <?php foreach ($servicios as $servicio): ?>
                                <tr>
                                    <td><?= htmlspecialchars($servicio['id']) ?></td>
                                    <td><?= htmlspecialchars($servicio['nombre']) ?></td>
                                    <td><?= htmlspecialchars($servicio['unidad']) ?></td>
                                    <td>XAF <?= number_format($servicio['precio_base'], 2) ?></td>
                                    <td>
                                        <a href="#"
                                            class="btn btn-sm <?= $servicio['activo'] ? 'btn-success' : 'btn-danger' ?> toggle-estado"
                                            data-id="<?= $servicio['id'] ?>"
                                            data-estado="<?= $servicio['activo'] ? '1' : '0' ?>">
                                            <i class="bi <?= $servicio['activo'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                                            <?= $servicio['activo'] ? 'Activado' : 'Desactivado' ?>
                                        </a>


                                    </td>
                                    <td>
                                        <a href="index.php?vista=editar_servicios&id=<?= $servicio['id'] ?>"
                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <?php if (strtolower(htmlspecialchars($_SESSION['usuario']['rol'])) === 'administrador'): ?>
                                            <a href="#" class="btn btn-sm btn-danger btn-eliminar" data-id="<?= $servicio['id'] ?>"
                                                title="Eliminar servicio">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        <?php endif; ?>



                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-muted text-center py-3">No se encontraron usuarios.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toggle-estado').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.dataset.id;
                const estadoActual = parseInt(btn.dataset.estado);

                const confirmar = confirm(`¿Estás seguro de ${estadoActual ? 'desactivar' : 'activar'} este servicio?`);
                if (!confirmar) return;

                const res = await fetch('api/toggle_estado_servicio.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, nuevo_estado: estadoActual ? 0 : 1 })
                });

                const data = await res.json();
                if (data.success) {
                    location.reload(); // refresca para ver el cambio
                } else {
                    alert(data.message);
                }
            });
        });


        const botonesEliminar = document.querySelectorAll('.btn-eliminar');

        botonesEliminar.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');

                if (confirm('¿Seguro que quieres eliminar este servicio?')) {
                    fetch(`api/eliminar_servicios.php?id=${id}`, {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                // Opcional: eliminar la fila de la tabla sin recargar
                                const fila = btn.closest('tr');
                                if (fila) fila.remove();
                            } else {
                                alert(data.message || 'Error al eliminar el servicio.');
                            }
                        })
                        .catch(() => alert('Error en la petición.'));
                }
            });
        });
    });
</script>