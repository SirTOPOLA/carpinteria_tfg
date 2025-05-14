<?php

// Búsqueda opcional
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

// Consulta SQL extendida
$sql = "SELECT mm.*, 
               m.nombre AS nombre_material,
               p.nombre AS nombre_proyecto,
               e.nombre AS nombre_responsable,
               e.apellido AS apellido_responsable
        FROM movimientos_material mm
        JOIN materiales m ON mm.material_id = m.id
        LEFT JOIN producciones prod ON mm.produccion_id = prod.id
        LEFT JOIN proyectos p ON prod.proyecto_id = p.id
        LEFT JOIN empleados e ON prod.responsable_id = e.id";

$params = [];
if ($busqueda !== '') {
    $sql .= " WHERE m.nombre LIKE :buscar OR mm.motivo LIKE :buscar OR p.nombre LIKE :buscar OR e.nombre LIKE :buscar";
    $params[':buscar'] = "%$busqueda%";
}

$sql .= " ORDER BY mm.fecha DESC";

$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt->execute();
$movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="content" class="container-fluid py-4">
    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-kanban-fill me-2"></i> Gestión de Movimientos
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar movimientos..." id="buscador-movimientos">
            </div>
            <a href="index.php?vista=registrar_movimientos" class="btn btn-secondary">

                <i class="bi bi-plus"> </i>Nuevo Movimientos</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th>Material</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Motivo</th>
                            <th>Proyecto</th>
                            <th>Responsable</th>
                            <th class="text-center"><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($movimientos)): ?>
                           
                            <?php foreach ($movimientos as $mov): ?>
                                <tr>
                                    <td><?= htmlspecialchars($mov['id']) ?></td>
                                    <td><?= htmlspecialchars($mov['nombre_material']) ?></td>
                                    <td>
                                        <span
                                            class="badge bg-<?= $mov['tipo_movimiento'] === 'entrada' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($mov['tipo_movimiento']) ?>
                                        </span>
                                    </td>
                                    <td><?= $mov['cantidad'] ?></td>
                                    <td><?= $mov['fecha'] ?></td>
                                    <td><?= htmlspecialchars($mov['motivo']) ?></td>
                                    <td><?= htmlspecialchars($mov['nombre_proyecto'] ?? 'N/D') ?></td>
                                    <td><?= htmlspecialchars(($mov['nombre_responsable'] ?? '') . ' ' . ($mov['apellido_responsable'] ?? '')) ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="index.php?vista=editar_movimientos&id=<?= $mov['id'] ?>"
                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <!-- <a href="eliminar_movimientos_inventario.php?id " class="btn btn-sm btn-danger"
                                       title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este movimiento?');">
                                        <i class="bi bi-trash"></i>
                                    </a> -->
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No hay resultados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> 

</div>