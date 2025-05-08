<?php
require_once("../includes/conexion.php");


// Consulta paginada
$sql = "SELECT * FROM proveedores ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <h4 class="mb-0">Lista de Proveedores</h4>
            <a href="registrar_proveedores.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo Proveedor
            </a>
        </div>


        <!-- Tabla -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Dirección</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($proveedores): ?>
                        <?php foreach ($proveedores as $proveedor): ?>
                            <tr>
                                <td><?= $proveedor['id'] ?></td>
                                <td><?= htmlspecialchars($proveedor['nombre']) ?></td>
                                <td><?= htmlspecialchars($proveedor['contacto']) ?></td>
                                <td><?= htmlspecialchars($proveedor['telefono']) ?></td>
                                <td><?= htmlspecialchars($proveedor['email']) ?></td>
                                <td><?= htmlspecialchars($proveedor['direccion']) ?></td>
                                <td class="text-center">
                                    <a href="editar_proveedor.php?id=<?= $proveedor['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No se encontraron proveedores.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


    </div>
</main>
<?php include_once("../includes/footer.php"); ?>