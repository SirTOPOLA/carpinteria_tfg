<?php
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
include '../includes/conexion.php';

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

<main class="flex-grow-1 overflow-auto p-4">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-4">Movimientos de material</h2>
            <a href="Registrar_movimientos_material.php" class="btn btn-success" title="Nuevo Movimiento">
                <i class="bi bi-plus-circle"></i>
            </a>
        </div>

        

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Material</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                        <th>Motivo</th>
                        <th>Proyecto</th>
                        <th>Responsable</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($movimientos)): ?>
                        <tr>
                            <td colspan="8" class="text-center">No hay resultados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($movimientos as $mov): ?>
                            <tr>
                                <td><?= htmlspecialchars($mov['nombre_material']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $mov['tipo_movimiento'] === 'entrada' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($mov['tipo_movimiento']) ?>
                                    </span>
                                </td>
                                <td><?= $mov['cantidad'] ?></td>
                                <td><?= $mov['fecha'] ?></td>
                                <td><?= htmlspecialchars($mov['motivo']) ?></td>
                                <td><?= htmlspecialchars($mov['nombre_proyecto'] ?? 'N/D') ?></td>
                                <td><?= htmlspecialchars(($mov['nombre_responsable'] ?? '') . ' ' . ($mov['apellido_responsable'] ?? '')) ?></td>
                                <td class="text-center">
                                    <a href="editar_movimientos_inventario.php?id=<?= $mov['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="eliminar_movimientos_inventario.php?id=<?= $mov['id'] ?>" class="btn btn-sm btn-danger"
                                       title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este movimiento?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<?php include '../includes/footer.php'; ?>
