<?php
require_once("../includes/conexion.php");

// ========================
// PARÁMETROS
// ========================
$buscar = trim($_GET['buscar'] ?? '');
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$por_pagina = 10; // Cambiar este valor para ajustar la cantidad de resultados por página
$offset = ($pagina - 1) * $por_pagina;

$where = '';
$params = [];

if (!empty($buscar)) {
    $where = "WHERE u.usuario LIKE :buscar OR e.nombre LIKE :buscar OR e.apellido LIKE :buscar";
    $params[':buscar'] = "%$buscar%";
}

// ========================
// TOTAL DE REGISTROS
// ========================
$sql_total = "
    SELECT COUNT(*) 
    FROM usuarios u
    LEFT JOIN empleados e ON u.empleado_id = e.id
    $where
";
$stmt_total = $pdo->prepare($sql_total);
if (!empty($params)) {
    $stmt_total->execute($params);
} else {
    $stmt_total->execute();
}
$total = $stmt_total->fetchColumn();
$total_paginas = ceil($total / $por_pagina);

// ========================
// CONSULTA PAGINADA
// ========================
$sql = "
    SELECT u.id, u.usuario, u.rol, u.activo, e.nombre AS empleado_nombre, e.apellido AS empleado_apellido
    FROM usuarios u
    LEFT JOIN empleados e ON u.empleado_id = e.id
    $where
    ORDER BY u.id DESC
    LIMIT :offset, :limite
";
$stmt = $pdo->prepare($sql);

if (!empty($params)) {
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v, PDO::PARAM_STR);
    }
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limite', $por_pagina, PDO::PARAM_INT);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
// dashboard.php principal
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';
?>
<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Usuarios Registrados</h4>
            <a href="registrar_usuarios.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo Usuario
            </a>
        </div>

        <!-- BUSCADOR -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-10 col-8">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar por usuario o empleado"
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
                        <th>Usuario</th>
                        <th>Empleado</th>
                        <th>Rol</th>
                        <th>Activo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= $usuario['id'] ?></td>
                                <td><?= htmlspecialchars($usuario['usuario']) ?></td>
                                <td><?= htmlspecialchars($usuario['empleado_nombre'] . ' ' . $usuario['empleado_apellido']) ?>
                                </td>
                                <td><?= htmlspecialchars($usuario['rol']) ?></td>
                                <td class="text-center">
                                    <!-- Botón de Activar/Desactivar -->
                                    <a href="activar_desactivar_usuario.php?id=<?= $usuario['id'] ?>"
                                        class="btn btn-sm <?= $usuario['activo'] ? 'btn-success' : 'btn-danger' ?>"
                                        onclick="return confirm('¿Está seguro de <?= $usuario['activo'] ? 'desactivar' : 'activar' ?> este usuario?');">
                                        <i class="bi <?= $usuario['activo'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                                        <?= $usuario['activo'] ? 'Activado' : 'Desactivado' ?>
                                    </a>
                                </td>

                                <td class="text-center">
                                    <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="eliminar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Está seguro de eliminar este usuario?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No se encontraron usuarios.</td>
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
</main>

<?php include_once("../includes/footer.php"); ?>