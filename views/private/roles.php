<?php

 // Si no hay sesión → redirige a login
if (!isset($_SESSION['usuario'])) {
    $_SESSION['alerta'] = "Debes registrarte para continuar con esta petición.";
    header("Location: login.php");
    exit;
}

 
    $sql = "SELECT * FROM roles";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
 
?>



<div id="content" class="container-fliud">
    <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h4 class="fw-bold mb-0 text-white">
                <i class="bi bi-person-vcard-fill me-2"></i> Gestión de Roles
            </h4>
            <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar rol..." id="buscador-roles">
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaRoles" class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-shield-lock-fill me-1"></i>Rol</th>
                            <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($roles)): ?>
                            <?php foreach ($roles as $rol): ?>
                                <tr>
                                    <td>
                                          <?= htmlspecialchars($rol["id"]) ?>
                                    </td>
                                    <td>
                                          <?= htmlspecialchars($rol["nombre"]) ?>
                                    </td>
                                    <td>
                                        <a href="index.php?vista=editar_roles&id=<?= urlencode($rol["id"]) ?>"
                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-muted text-center py-3">No se encontraron resultados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>