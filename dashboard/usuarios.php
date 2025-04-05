<?php
require_once("../includes/conexion.php");

// ========================
// PARÁMETROS
// ========================
$buscar = trim($_GET['buscar'] ?? '');
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$por_pagina = 10;
$offset = ($pagina - 1) * $por_pagina;

// ========================
// TOTAL DE REGISTROS
// ========================
if (!empty($buscar)) {
    $stmt_total = $pdo->prepare("SELECT COUNT(*) FROM usuarios u WHERE u.nombre LIKE :buscar OR u.correo LIKE :buscar");
    $buscar_param = "%$buscar%";
    $stmt_total->bindParam(':buscar', $buscar_param, PDO::PARAM_STR);
    $stmt_total->execute();
} else {
    $stmt_total = $pdo->query("SELECT COUNT(*) FROM usuarios");
}
$total = $stmt_total->fetchColumn();
$total_paginas = ceil($total / $por_pagina);

// ========================
// CONSULTA PAGINADA
// ========================
if (!empty($buscar)) {
    $sql = "
        SELECT u.id, u.nombre, u.correo, u.estado, u.fecha_creacion, r.nombre AS rol
        FROM usuarios u
        JOIN roles r ON u.rol_id = r.id
        WHERE u.nombre LIKE :buscar OR u.correo LIKE :buscar
        ORDER BY u.fecha_creacion DESC
        LIMIT :offset, :limite
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':buscar', $buscar_param, PDO::PARAM_STR);
} else {
    $sql = "
        SELECT u.id, u.nombre, u.correo, u.estado, u.fecha_creacion, r.nombre AS rol
        FROM usuarios u
        JOIN roles r ON u.rol_id = r.id
        ORDER BY u.fecha_creacion DESC
        LIMIT :offset, :limite
    ";
    $stmt = $pdo->prepare($sql);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limite', $por_pagina, PDO::PARAM_INT);
$stmt->execute();

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Continúa igual a partir de aquí con el HTML... -->

<?php include_once("../includes/header.php"); ?> 
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Usuarios Registrados</h4>
        <div>
            <a href="registrar_usuarios.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo Usuario
            </a>
            <a href="roles.php" class="btn btn-primary">
                <i class="bi bi-person-lines-fill"></i> Lista de roles
            </a>
        </div>
    </div>

    <!-- BUSCADOR -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-10 col-8">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o correo"
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
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($usuarios) > 0): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario['id'] ?></td>
                            <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                            <td><?= htmlspecialchars($usuario['correo']) ?></td>
                            <td><?= htmlspecialchars($usuario['rol']) ?></td>
                            <td>
                                <span class="badge bg-<?= $usuario['estado'] === 'activo' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($usuario['estado']) ?>
                                </span>
                            </td>
                            <td><?= date("d/m/Y H:i", strtotime($usuario['fecha_creacion'])) ?></td>
                            <td class="text-center">
                                <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="eliminar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este usuario?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron usuarios.</td>
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

<?php include_once("../includes/footer.php"); ?>
