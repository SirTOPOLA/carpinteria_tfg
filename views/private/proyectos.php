<?php
require_once("../includes/conexion.php");

$sql = "SELECT * FROM proyectos ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Listado de Proyectos</h4>
        <a href="registrar_proyectos.php" class="btn btn-primary">+ Nuevo Proyecto</a>
    </div>



    <!-- Tabla -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>descripcion</th>
                    <th>Estado</th>
                    <th>fecha inicio</th>
                    <th>Fecha entrega</th>
                    <th>Creado en</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($proyectos) === 0): ?>
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron proyectos.</td>
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

                                <!-- Ver proyecto -->
                                <a href="solicitud_proyecto.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-info"
                                    title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <!-- Asignar materiales al proyecto -->
                                <a href="registrar_proyecto_materiales.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-secondary">
                                    <i class="bi bi-files"></i> asignar Material
                                </a>
                                <!-- Editar proyecto -->
                                <a href="editar_proyecto.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>


<?php include '../includes/footer.php'; ?>