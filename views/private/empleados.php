<?php
 // Si no hay sesión → redirige a login
 if (!isset($_SESSION['usuario'])) {
     $_SESSION['alerta'] = "Debes registrarte para continuar con esta petición.";
    header("Location: login.php");
    exit;
}
 
 
$sql = "SELECT *  FROM empleados ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
 

?>


<div id="content" class="container-fluid py-4">

    <div class="card shadow-sm border-0">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <h4 class="fw-bold mb-0 text-white">
                        <i class="bi bi-people-fill me-2"></i>Listado de Empleados
                    </h4>
                    <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar empleado..." id="buscador-empleado">
                    </div>
                    <a href="index.php?vista=registrar_empleado" class="btn btn-secondary shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Nuevo empleado
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-custom">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash me-1"></i>ID</th>
                                <th><i class="bi bi-person-vcard me-1"></i>Nombre</th>
                                <th><i class="bi bi-qr-code me-1"></i>Código</th>
                                <th><i class="bi bi-envelope-at me-1"></i>Email</th>
                                <th><i class="bi bi-telephone me-1"></i>Teléfono</th>
                                <th><i class="bi bi-geo-alt me-1"></i>Dirección</th>
                                <th><i class="bi bi-clock-history me-1"></i>Horario</th>
                                <th><i class="bi bi-cash-stack me-1"></i>Salario</th>
                                <th><i class="bi bi-calendar-check me-1"></i>Ingreso</th>
                                <th><i class="bi bi-tools me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php if ($empleados): ?>

                                <?php foreach ($empleados as $e): ?>
                                    <tr>
                                        <td><?= $e['id'] ?></td>
                                        <td><?= htmlspecialchars($e['nombre'] . ' ' . $e['apellido']) ?></td>
                                        <td><?= htmlspecialchars($e['codigo']) ?></td>
                                        <td><?= htmlspecialchars($e['email']) ?></td>
                                        <td><?= htmlspecialchars($e['telefono']) ?></td>
                                        <td><?= htmlspecialchars($e['direccion']) ?></td>
                                        <td><?= htmlspecialchars($e['horario_trabajo']) ?></td>
                                        <td><?= htmlspecialchars($e['salario'] ?? 'Sin definir') ?></td>
                                        <td><?= date('d/m/Y', strtotime($e['fecha_ingreso'])) ?></td>
                                        <td>
                                            <a href="index.php?vista=editar_empleado&id=<?= urlencode($e['id']) ?>"
                                                class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-muted text-center py-3">No se encontraron resultados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer text-muted">
                <!-- Paginación futura aquí -->
            </div>
        </div>
    </div>

</div>