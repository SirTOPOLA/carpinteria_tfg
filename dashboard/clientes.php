<?php
require_once("../includes/conexion.php");


// ========================
// CONSULTA PAGINADA
// ========================
$sql = "
    SELECT *
    FROM clientes ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <h4 class="mb-0">Clientes Registrados</h4>
            <a href="registrar_cliente.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo Cliente
            </a>
        </div>


        <!-- TABLA -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>codigo_acceso</th>
                        <th>DIP</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Fecha</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($clientes) > 0): ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?= $cliente['id'] ?></td>
                                <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                                <td><?= htmlspecialchars($cliente['email']) ?></td>
                                <td><?= htmlspecialchars($cliente['codigo_acceso']) ?></td>
                                <td><?= htmlspecialchars($cliente['codigo']) ?></td>
                                <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                                <td><?= htmlspecialchars($cliente['direccion']) ?></td>
                                <td><?= date("d/m/Y H:i", strtotime($cliente['creado_en'])) ?></td>
                                <td class="text-center">
                                    <a href="editar_cliente.php?id=<?= $cliente['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="eliminar_cliente.php?id=<?= $cliente['id'] ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Está seguro de eliminar este cliente?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No se encontraron clientes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


    </div>
</main>
<?php include_once("../includes/footer.php"); ?>