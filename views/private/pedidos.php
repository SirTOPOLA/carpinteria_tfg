<?php


/* if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: pedidos.php");
    exit;
} */

//$id = (int) $_GET['id'];

/* $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0)
    die("ID inválido.");

 */
$sql = "SELECT sp.*,
        c.nombre AS cliente,
        p.nombre AS proyecto    
        FROM solicitudes_proyecto sp
        INNER JOIN clientes c ON sp.cliente_id = c.id
        INNER JOIN proyectos p ON sp.proyecto_id = p.id
         ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetch(PDO::FETCH_ASSOC);

/* $sql = "SELECT sp.*,
        c.nombre AS cliente
        FROM solicitudes_pedidos sp
        INNER JOIN clientes c ON sp.cliente_id = c.id 
         ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$solicitudes_pedidos = $stmt->fetch(PDO::FETCH_ASSOC);

 */

/* if (!$pedidos) {
    header("Location: pedidoss.php");
    exit;
} */
?>


<div id="content" class="container-fluid py-4">

<div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-kanban-fill me-2"></i> Gestión de Pedidos
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar pedido..." id="buscador-pedidoss">
            </div>
            <a href="index.php?vista=registrar_pedidos" class="btn btn-secondary">

                <i class="bi bi-plus"> </i>Nuevo pedido</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-card-heading me-1"></i>Proyecto</th>
                            <th><i class="bi bi-file-text me-1"></i>Cliente</th>
                            <th><i class="bi bi-flag-fill me-1"></i>Descripción</th>
                            <th><i class="bi bi-calendar-event me-1"></i>Creado</th>
                            <th><i class="bi bi-calendar-check me-1"></i>Estado</th>
                            <th><i class="bi bi-clock-history me-1"></i>Creado</th>
                            <th><i class="bi bi-clock-history me-1"></i>Coste</th>
                            <th class="text-center"><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pedidos) === 0): ?>
                          
                            <?php foreach ($pedidos as $p): ?>
                                <tr>
                                    <td><?= $p['id'] ?></td>
                                    <td><?= htmlspecialchars($p['proyecto']) ?></td>
                                    <td><?= htmlspecialchars($p['cliente']) ?></td>
                                    <td><?= htmlspecialchars($p['descripcion']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($p['fecha_solicitud'])) ?></td>
                                    <td><?= ucfirst($p['estado']) ?></td> 
                                    <td>XAF <?= number_format($p['estimacion_total'], 1) ?></td>
                                     <td class="text-center">
                                        <a href="index.php?vista=destalles_pedidos&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-info"
                                            title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="index.php?vista=editar_pedidos&id=<?= $p['id'] ?>"
                                            class="btn btn-sm btn-outline-primary"  >
                                            <i class="bi bi-files"></i>
                                        </a>
                                        
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">No se encontraron pedidos.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



</div>