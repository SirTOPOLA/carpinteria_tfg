<?php
 

// Obtener producciones disponibles
$sql = "SELECT 
                prod.*,
                proy.estado AS estado_proyecto, 
                proy.nombre AS proyecto_nombre, 
                emp.nombre AS empleado_nombre
                FROM producciones prod
                LEFT JOIN proyectos proy ON prod.proyecto_id = proy.id                 
                LEFT JOIN empleados emp ON prod.responsable_id = emp.id
                ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$producciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

 
<div id="content" class="container-fluid py-4">
    
    
    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-boxes me-2"></i> Gestión de Producciones
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar producción..." id="buscador-producciones">
            </div>
            <a href="index.php?vista=registrar_producciones" class="btn btn-secondary">
                <i class="bi bi-plus-circle"></i> Nueva Producción
            </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th><i class="bi bi-hash me-1"></i>ID</th>
                        <th><i class="bi bi-folder2-open me-1"></i>Proyecto</th>
                        <th><i class="bi bi-calendar-event me-1"></i>Inicio</th>
                        <th><i class="bi bi-calendar-check me-1"></i>Fin</th>
                        <th><i class="bi bi-flag-fill me-1"></i>Estado</th>
                        <th><i class="bi bi-cpu me-1"></i>Etapa</th>
                        <th><i class="bi bi-person-fill-gear me-1"></i>Responsable</th>
                        <th><i class="bi bi-clock me-1"></i>Creado</th>
                        <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($producciones) === 0): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">No se encontraron resultados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($producciones as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>
                                <td><?= htmlspecialchars($p['proyecto_nombre']) ?></td>
                                <td><?= htmlspecialchars($p['fecha_inicio']) ?></td>
                                <td><?= htmlspecialchars($p['fecha_fin']) ?></td>
                                <td><?= htmlspecialchars($p['estado_proyecto']) ?></td>
                                <td>
                               
                                    <?= htmlspecialchars($p['estado']) ?>

                                </td>
                                <td><?= htmlspecialchars($p['empleado_nombre']) ?></td>
                                <td><?= htmlspecialchars($p['created_at']) ?></td>
                                <td>
                                    <a href="index.php?vista=editar_producciones&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                   <!--  <a href="registrar_proceso_produccionesid=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary" title="Procesar">
                                        <i class="bi bi-play-circle"></i>
                                    </a> -->
                                    
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
 


 