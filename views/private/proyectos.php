<?php


$sql = "SELECT * FROM proyectos ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<div id="content" class="container-fluid py-4">
    <!-- Contenedor -->
    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-kanban-fill me-2"></i> Lista de Proyectos
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar proyecto..." id="buscador-proyectos">
            </div>
            <a href="index.php?vista=registrar_proyectos" class="btn btn-secondary">

                <i class="bi bi-plus"> </i>Nuevo Proyecto</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-card-heading me-1"></i>Nombre</th>
                            <th><i class="bi bi-file-text me-1"></i>Descripción</th>
                            <th><i class="bi bi-flag-fill me-1"></i>Estado</th>
                            <th><i class="bi bi-calendar-event me-1"></i>Inicio</th>
                            <th><i class="bi bi-calendar-check me-1"></i>Entrega</th>
                            <th><i class="bi bi-clock-history me-1"></i>Creado</th>
                            <th class="text-center"><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($proyectos) === 0): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">No se encontraron proyectos.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($proyectos as $p): ?>
                                <tr>
                                    <td><?= $p['id'] ?></td>
                                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                                    <td><?= htmlspecialchars($p['descripcion']) ?></td>
                                    <td><?= ucfirst($p['estado']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($p['fecha_inicio'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($p['fecha_entrega'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($p['creado_en'])) ?></td>
                                    <td class="text-center">
                                         
                                        <a href="index.php?vista=registrar_movimientos&id=<?= $p['id'] ?>"
                                            class="btn btn-sm btn-outline-secondary" title="Asignar material">
                                            <i class="bi bi-files"></i>
                                        </a>
                                        <a href="index.php?vista=editar_proyectos&id=<?= $p['id'] ?>"
                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>