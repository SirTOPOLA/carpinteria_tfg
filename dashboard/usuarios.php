<?php
require_once("../includes/conexion.php");
 

 //obtener los usuarios
$sql = "SELECT 
                    u.*,
                    r.nombre AS rol,
                    e.nombre AS empleado_nombre,
                    e.apellido AS empleado_apellido
                FROM usuarios u
                LEFT JOIN roles r ON u.rol_id = r.id
                LEFT JOIN empleados e ON u.empleado_id = e.id
            ";


$stmt = $pdo->prepare($sql);
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
                                    <a href="../php/activar_desactivar_usuario.php?id=<?= $usuario['id'] ?>"
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

   
    </div>
</main>

<?php include_once("../includes/footer.php"); ?>