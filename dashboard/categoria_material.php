<?php
require_once("../includes/conexion.php");

$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$sql = "SELECT * FROM categorias_materiales WHERE 1";

$params = [];
if ($busqueda !== '') {
    $sql .= " AND nombre LIKE :buscar";
    $params[':buscar'] = "%$busqueda%";
}

$sql .= " ORDER BY nombre DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <div>
            <h4>Categorías de Materiales</h4>
            <div class="text-end pb-4">
                <a href="compras.php" class="btn btn-secondary" title="Ir a Lista de Compras">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <a href="registrar_categoria_material.php" class="btn btn-success" title="Nueva Categoría">
                    <i class="bi bi-plus-circle"></i>
                </a>

            </div>
        </div>

        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar categoría..."
                    value="<?= htmlspecialchars($busqueda) ?>">
                <button class="btn btn-outline-primary">Buscar</button>
            </div>
        </form>


        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $cat): ?>
                    <tr>
                        <td><?= $cat['id'] ?></td>
                        <td><?= htmlspecialchars($cat['nombre']) ?></td>

                        <td>
                            <a href="editar_categoria_material.php?id=<?= $cat['id'] ?>"
                                class="btn btn-sm btn-primary">Editar</a>
                            <a href="eliminar_categoria_material.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar esta categoría?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
    <?php include_once("../includes/footer.php"); ?>