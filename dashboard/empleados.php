<?php
require_once("../includes/conexion.php");

$busqueda = trim($_GET['busqueda'] ?? '');




// Obtener empleados paginados
$sql = "SELECT *  FROM empleados ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">

    <div class="container">

        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-3">Listado de Empleados</h4>
            <a href="registrar_empleado.php" class="btn btn-success">
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
                                    <a href="editar_empleado.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-warning">Editar</a>

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