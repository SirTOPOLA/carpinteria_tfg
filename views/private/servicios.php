<?php
require_once("../includes/conexion.php");

// ========================
// PARÁMETROS DE BÚSQUEDA Y PAGINACIÓN
// ========================
$busqueda = trim($_GET['buscar'] ?? '');
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$registros_por_pagina = 10;
$offset = ($pagina - 1) * $registros_por_pagina;

// ========================
// FILTRO DE BÚSQUEDA
// ========================
$where = '';
$params = [];

if (!empty($busqueda)) {
    $where = "WHERE nombre LIKE :buscar";
    $params[':buscar'] = "%$busqueda%";
}

// ========================
// TOTAL DE RESULTADOS
// ========================
$total_stmt = $pdo->prepare("SELECT COUNT(*) FROM servicios $where");
$total_stmt->execute($params);
$total_resultados = $total_stmt->fetchColumn();
$total_paginas = ceil($total_resultados / $registros_por_pagina);

// ========================
// CONSULTA DE SERVICIOS
// ========================
$query = "SELECT id, nombre, descripcion, precio, fecha_creacion 
          FROM servicios 
          $where 
          ORDER BY id ASC 
          LIMIT :offset, :limite";
$stmt = $pdo->prepare($query);

// Bind de parámetros
foreach ($params as $clave => $valor) {
    $stmt->bindValue($clave, $valor, PDO::PARAM_STR);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limite', $registros_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
?>
   <div class="container-fluid py-4">
        <!-- BARRA DE ACCIONES -->
        <div class="d-flex justify-content-between align-items-center p-2 mb-3">
            <h4 class="mb-0">Listado de Servicios</h4>
            <div>
                <a href="registrar_servicios.php" class="btn btn-success me-2">
                    <i class="bi bi-plus-circle"></i> Nuevo Servicio
                </a>
            </div>
        </div>

        <!-- BUSCADOR -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-10 col-8">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar servicio por nombre"
                       value="<?= htmlspecialchars($busqueda) ?>">
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
                        <th>Precio (Bs)</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($servicios) > 0): ?>
                    <?php foreach ($servicios as $s): ?>
                        <tr>
                            <td><?= htmlspecialchars($s['id']) ?></td>
                            <td><?= htmlspecialchars($s['nombre']) ?></td>
                            <td><?= htmlspecialchars($s['descripcion']) ?></td>
                            <td><?= number_format($s['precio'], 2, ',', '.') ?></td>
                            <td><?= date('d/m/Y', strtotime($s['fecha_creacion'])) ?></td>
                            <td>
                                <a href="editar_servicio.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-warning me-1" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="eliminar_servicio.php?id=<?= $s['id'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('¿Deseas eliminar este servicio?')"
                                   title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron servicios.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN -->
        <?php if ($total_paginas > 1): ?>
            <nav aria-label="Paginación de servicios">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                            <a class="page-link" href="?buscar=<?= urlencode($busqueda) ?>&pagina=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
 
<?php include_once("../includes/footer.php"); ?>
