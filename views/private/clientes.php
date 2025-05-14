<?php



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



<div id="content" class="container-fluid">
    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-person-vcard-fill me-2"></i>Gestión de Clientes
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar cliente..." id="buscador-cliente">
            </div>
            <a href="index.php?vista=registrar_clientes" class="btn btn-secondary mb-3"><i class="bi bi-plus"></i>
                Nuevo
        Cliente</a>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaRoles" class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-person"></i> Nombre</th>

                            <th>Correo</th>
                            <th>codigo_acceso</th>
                            <th>DIP</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Fecha</th>
                            <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
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
                                        <a href="index.php?vista=editar_clientes&id=<?= $cliente['id'] ?>" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil-square"></i>
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
    </div>

  

</div>