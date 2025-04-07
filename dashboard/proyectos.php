<?php
require_once("../includes/conexion.php");

// Buscador
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : "";

// Paginación
$por_pagina = 10;
$pagina = isset($_GET['pagina']) ? max((int) $_GET['pagina'], 1) : 1;
$inicio = ($pagina - 1) * $por_pagina;

// Total de proyectos
$sql_total = "SELECT COUNT(*) FROM proyectos p
JOIN clientes c ON p.cliente_id = c.id
WHERE p.nombre LIKE :busqueda1 OR c.nombre LIKE :busqueda2";
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->bindValue(':busqueda1', "%$busqueda%", PDO::PARAM_STR);
$stmt_total->bindValue(':busqueda2', "%$busqueda%", PDO::PARAM_STR);
$stmt_total->execute();
$total_proyectos = $stmt_total->fetchColumn();
$total_paginas = ceil($total_proyectos / $por_pagina);

// NOTA: aquí usamos directamente los enteros en el SQL para evitar el error
$sql = "SELECT p.*, c.nombre AS cliente
        FROM proyectos p
        JOIN clientes c ON p.cliente_id = c.id
        WHERE p.nombre LIKE :busqueda1 OR c.nombre LIKE :busqueda2
        ORDER BY p.fecha_inicio DESC
        LIMIT $inicio, $por_pagina"; // aquí no usamos bindValue
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':busqueda1', "%$busqueda%", PDO::PARAM_STR);
$stmt->bindValue(':busqueda2', "%$busqueda%", PDO::PARAM_STR);
$stmt->execute();
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Listado de Proyectos</h4>
            <a href="registrar_proyecto.php" class="btn btn-primary">+ Nuevo Proyecto</a>
        </div>

        <!-- Buscador -->
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="busqueda" class="form-control"
                    placeholder="Buscar por nombre de proyecto o cliente" value="<?= htmlspecialchars($busqueda) ?>">
                <button type="submit" class="btn btn-outline-secondary">Buscar</button>
            </div>
        </form>

        <!-- Tabla -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Cliente</th>
                        <th>Fecha creación</th>
                        <th>Estado</th>
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
                                <td><?= htmlspecialchars($p['cliente']) ?></td>
                                <td><?= date('d/m/Y', strtotime($p['fecha_inicio'])) ?></td>
                                <td><?= ucfirst($p['estado']) ?></td>
                                <td class="text-center">

                                    <!-- Ver proyecto -->
                                    <a href="detalles_proyecto.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-info"
                                        title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <!-- Editar proyecto -->
                                    <a href="editar_proyecto.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning"
                                        title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Eliminar proyecto -->
                                    <a href="eliminar_proyecto.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger"
                                        title="Eliminar"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar este proyecto?');">
                                        <i class="bi bi-trash"></i>
                                    </a>


                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <?php if ($total_paginas > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $i ?>&busqueda=<?= urlencode($busqueda) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>