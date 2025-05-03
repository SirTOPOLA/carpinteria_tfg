<?php
require_once("../includes/conexion.php");

$busqueda = trim($_GET['busqueda'] ?? '');
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 10;
$offset = ($pagina - 1) * $por_pagina;

// Conteo total con filtro de búsqueda
$condicion = "1";
$params = [];
if ($busqueda !== '') {
    $condicion = "(e.nombre LIKE :busqueda OR e.apellido LIKE :busqueda OR e.codigo LIKE :busqueda OR e.email LIKE :busqueda)";
    $params[':busqueda'] = "%$busqueda%";
}

$total_sql = "SELECT COUNT(*) FROM empleados e WHERE $condicion";
$total_stmt = $pdo->prepare($total_sql);
$total_stmt->execute($params);
$total = $total_stmt->fetchColumn();

// Obtener empleados paginados
$sql = "SELECT e.*   FROM empleados e 
          
        WHERE $condicion 
        ORDER BY e.created_at DESC 
        LIMIT :offset, :limite";
$stmt = $pdo->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limite', $por_pagina, PDO::PARAM_INT);
$stmt->execute();
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_paginas = ceil($total / $por_pagina);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container">
        <h4 class="mb-3">Listado de Empleados</h4>
        <a href="registrar_empleado.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo empleado
            </a>

        <form method="get" class="row g-2 mb-3">
            <div class="col-md-10">
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar por nombre, código o email" value="<?= htmlspecialchars($busqueda) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
        </form>

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
                        <tr><td colspan="7" class="text-center">No se encontraron resultados.</td></tr>
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
                                    <a href="eliminar_empleado.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este empleado?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_paginas > 1): ?>
            <nav>
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                            <a class="page-link" href="?busqueda=<?= urlencode($busqueda) ?>&pagina=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
