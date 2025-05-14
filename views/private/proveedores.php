<?php
// Si no hay sesión → redirige a login
if (!isset($_SESSION['usuario'])) {
    $_SESSION['alerta'] = "Debes registrarte para continuar con esta petición.";
    header("Location: login.php");
    exit;
}

// Consulta paginada
$sql = "SELECT * FROM proveedores ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div id="content" class="container-fluid py-4">
    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-person-vcard-fill me-2"></i>Gestión de Proveesores
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar proveedor..." id="buscador-proveedor">
            </div>
            <a href="index.php?vista=registrar_proveedores" class="btn btn-secondary mb-3"><i class="bi bi-plus"></i>
                Nuevo
                proveedor</a>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaRoles" class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-person"></i> Nombre</th>
                            <th>Contacto</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Dirección</th>
                            <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
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
                                        <a href="index.php?vista=editar_proveedores&id=<?= $proveedor['id'] ?>"
                                            class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil-square"></i>
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
    </div>


</div>


 