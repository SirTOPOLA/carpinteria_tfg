<?php
include '../includes/conexion.php';
include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/nav.php';

// Parámetros de búsqueda
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

// Paginación
$por_pagina = 10;
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($pagina - 1) * $por_pagina;

// Conteo total para paginación
$condicion = $busqueda ? "WHERE m.nombre LIKE :buscar" : "";
$sql_total = "SELECT COUNT(*) FROM movimientos_inventario mi JOIN materiales m ON mi.material_id = m.id $condicion";
$stmt_total = $pdo->prepare($sql_total);
if ($busqueda)
    $stmt_total->bindValue(':buscar', "%$busqueda%");
$stmt_total->execute();
$total_resultados = $stmt_total->fetchColumn();
$total_paginas = ceil($total_resultados / $por_pagina);

// Consulta principal
$sql = "
  SELECT mi.*, m.nombre AS nombre_material 
  FROM movimientos_inventario mi
  JOIN materiales m ON mi.material_id = m.id
  $condicion
  ORDER BY mi.fecha DESC
  LIMIT $offset, $por_pagina
";
$stmt = $pdo->prepare($sql);
if ($busqueda)
    $stmt->bindValue(':buscar', "%$busqueda%");
$stmt->execute();
$movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="flex-grow-1 overflow-auto p-4">
    <div class="container ">


        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-4">Movimientos de Inventario</h2>
            <div>
                <!--  <a href="registrar_movimiento_inventario.php" class="btn btn-success" title="Nuevo Producto">
                    <i class="bi bi-plus-circle"></i>
                </a>
                <a href="categorias.php" class="btn btn-primary" title="Categorías de Productos">
                    <i class="bi bi-tags"></i>
                </a>
                  -->
                <a href="Registrar_movimientos_inventario.php" class="btn btn-success" title="Nuevo Movimiento">
                    <i class="bi bi-plus-circle"></i>
                </a>
            </div>
        </div>

        <!-- BUSCADOR -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-10 col-8">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o descripción"
                    value="Buscar">
            </div>
            <div class="col-md-2 col-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>


        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Material</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                        <th>Motivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($movimientos)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay resultados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($movimientos as $mov): ?>
                            <tr>
                                <td><?= $mov['id'] ?></td>
                                <td><?= htmlspecialchars($mov['nombre_material']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $mov['tipo'] === 'entrada' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($mov['tipo']) ?>
                                    </span>
                                </td>
                                <td><?= $mov['cantidad'] ?></td>
                                <td><?= $mov['fecha'] ?></td>
                                <td><?= htmlspecialchars($mov['motivo']) ?></td>
                                <td class="text-center">
                                    <a href="editar_movimientos_inventario.php?id=<?= $mov['id'] ?>" class="btn btn-sm btn-warning"
                                        title="Editar">
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

        <!-- Paginación -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $i ?>&buscar=<?= urlencode($busqueda) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav> 
    </div>
</main>

<?php include '../includes/footer.php'; ?>