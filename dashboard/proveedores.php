<?php
require_once("../includes/conexion.php");

// Parámetros
$buscar = trim($_GET['buscar'] ?? '');
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 10;
$offset = ($pagina - 1) * $por_pagina;

$where = '';
$params = [];

if (!empty($buscar)) {
    $where = "WHERE nombre LIKE :buscar OR correo LIKE :buscar";
    $params[':buscar'] = "%$buscar%";
}

// Total de registros
$stmt_total = $pdo->prepare("SELECT COUNT(*) FROM proveedores $where");
$stmt_total->execute($params);
$total = $stmt_total->fetchColumn();
$total_paginas = ceil($total / $por_pagina);

// Consulta paginada
$sql = "SELECT * FROM proveedores $where ORDER BY fecha_registro DESC LIMIT :offset, :limite";
$stmt = $pdo->prepare($sql);

foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, PDO::PARAM_STR);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limite', $por_pagina, PDO::PARAM_INT);
$stmt->execute();
$proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
// dashboard.php principal
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
include '../includes/conexion.php'; // Asegúrate de tener la conexión a base de datos aquí
?>
<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Lista de Proveedores</h4>
        <a href="registrar_proveedor.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nuevo Proveedor
        </a>
    </div>

    <!-- Buscador -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-10 col-8">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o correo" value="<?= htmlspecialchars($buscar) ?>">
        </div>
        <div class="col-md-2 col-4">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
        </div>
    </form>

    <!-- Tabla -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th>Fecha Registro</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($proveedores): ?>
                    <?php foreach ($proveedores as $proveedor): ?>
                        <tr>
                            <td><?= $proveedor['id'] ?></td>
                            <td><?= htmlspecialchars($proveedor['nombre']) ?></td>
                            <td><?= htmlspecialchars($proveedor['telefono']) ?></td>
                            <td><?= htmlspecialchars($proveedor['correo']) ?></td>
                            <td><?= htmlspecialchars($proveedor['direccion']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($proveedor['fecha_registro'])) ?></td>
                            <td class="text-center">
                                <a href="editar_proveedor.php?id=<?= $proveedor['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="eliminar_proveedor.php?id=<?= $proveedor['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Deseas eliminar este proveedor?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No se encontraron proveedores.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
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
</main>
<?php include_once("../includes/footer.php"); ?>
