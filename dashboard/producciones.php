<?php
require_once("../includes/conexion.php");
 
 

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

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">

    <div class="container">

        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-3">Listado de Producciones</h4>
            <a href="registrar_producciones.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nueva Producción
            </a>
            
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr> 
                        <th>ID</th>
                        <th>Proycto</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Estado Proyecto</th>
                        <th>Etapa Poducion</th>
                        <th>Responsable</th>
                        <th>Creado en</th> 
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($producciones) === 0): ?>
                        <tr>
                            <td colspan="7" class="text-center">No se encontraron resultados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($producciones as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>
                                <td><?= htmlspecialchars($p['proyecto_nombre']) ?></td>
                                <td><?= htmlspecialchars($p['fecha_inicio']) ?></td>
                                <td><?= htmlspecialchars($p['fecha_fin']) ?></td>
                                <td><?= htmlspecialchars($p['estado_proyecto']) ?></td>
                                <td><?= htmlspecialchars($p['estado']) ?></td>
                                <td><?= htmlspecialchars($p['empleado_nombre']) ?></td>
                                <td><?= htmlspecialchars($p['created_at']) ?></td>
                                 <td>
                                    <a href="editar_producciones.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                    <a href="registrar_proceso_produccion.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">Procesar</a>

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