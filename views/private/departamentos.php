<?php
require_once("../includes/conexion.php");

// ========================
// PARÁMETROS
// ========================
$buscar = trim($_GET['buscar'] ?? '');
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$por_pagina = 10;
$offset = ($pagina - 1) * $por_pagina;

$where = '';
$params = [];

if (!empty($buscar)) {
    $where = "WHERE nombre LIKE :buscar OR descripcion LIKE :buscar";
    $params[':buscar'] = "%$buscar%";
}

// ========================
// TOTAL DE REGISTROS
// ========================
$sql_total = "SELECT COUNT(*) FROM departamentos $where";
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute($params);
$total = $stmt_total->fetchColumn();
$total_paginas = ceil($total / $por_pagina);

// ========================
// CONSULTA PAGINADA
// ========================
$sql = "
    SELECT id, nombre, descripcion, creado_en
    FROM departamentos
    $where
    ORDER BY creado_en DESC
    LIMIT :offset, :limite
";
$stmt = $pdo->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, PDO::PARAM_STR);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limite', $por_pagina, PDO::PARAM_INT);
$stmt->execute();
$departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>
 
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Departamentos</h4>
            <a href="registrar_departamento.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo Departamento
            </a>
        </div>

        <!-- BUSCADOR -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-10 col-8">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o descripción"
                    value="<?= htmlspecialchars($buscar) ?>">
            </div>
            <div class="col-md-2 col-4">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
        </form>

        <!-- TABLA -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Creado en</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($departamentos): ?>
                        <?php foreach ($departamentos as $dpto): ?>
                            <tr>
                                <td><?= $dpto['id'] ?></td>
                                <td><?= htmlspecialchars($dpto['nombre']) ?></td>
                                <td><?= nl2br(htmlspecialchars($dpto['descripcion'])) ?></td>
                                <td><?= date("d/m/Y H:i", strtotime($dpto['creado_en'])) ?></td>
                                <td class="text-center">
                                    <a href="editar_departamento.php?id=<?= $dpto['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="eliminar_departamento.php?id=<?= $dpto['id'] ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Está seguro de eliminar este departamento?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No se encontraron departamentos.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN -->
        <?php if ($total_paginas > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                            <a class="page-link" href="?buscar=<?= urlencode($buscar) ?>&pagina=<?= $i ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
 

<?php include '../includes/footer.php'; ?>
