<?php
 

$busqueda = trim($_GET['busqueda'] ?? '');




// Obtener empleados paginados
$sql = "SELECT *  FROM empleados ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

 
<div id="content" class="container-fluid py-4">

        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-3">Listado de Empleados</h4>
            <a href="index.php?vista=registrar_empleado" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo empleado
            </a>
            
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Horario</th>
                        <th>Dalario</th>
                        <th>Fecha Ingreso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($empleados) === 0): ?>
                        <tr>
                            <td colspan="7" class="text-center">No se encontraron resultados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($empleados as $e): ?>
                            <tr>
                                <td><?= $e['id'] ?></td>
                                <td><?= htmlspecialchars($e['nombre'] . ' ' . $e['apellido']) ?></td>
                                <td><?= htmlspecialchars($e['codigo']) ?></td>
                                <td><?= htmlspecialchars($e['email']) ?></td>
                                <td><?= htmlspecialchars($e['telefono']) ?></td>
                                <td><?= htmlspecialchars($e['direccion']) ?></td>
                                <td><?= htmlspecialchars($e['horario_trabajo']) ?></td>
                                <td><?= htmlspecialchars($e['salario'] ?? 'Sin definir') ?></td>
                                <td><?= $e['fecha_ingreso'] ?></td>
                                <td>
                                <a href="index.php?vista=editar_empleado&id=<?= urlencode($e["id"]) ?>" class="btn btn-sm btn-outline-warning"
                                    title="Editar">
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
 

 