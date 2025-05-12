 

<div id="content" class="container-fliud">
    <div id="navContent" class="row  mb-2 p-2">
        <!-- BARRA DE ACCIONES -->
        <h4 class="mb-4 text-start">Listado de Roles</h4>
       <!--  <div class="text-end mb-4 p-2">
            <a href="registrar_rol.php" class="btn btn-success me-2">
                <i class="bi bi-shield-plus"></i> Nuevo Rol
            </a>
            <a href="usuarios.php" class="btn btn-primary">
                <i class="bi bi-person-lines-fill"></i> Lista de Usuarios
            </a>
        </div> -->


        <!-- BUSCADOR -->
        <div class="mb-3">
            <input type="search" id="buscador" class="form-control bg-white text-white border-secondary"
                placeholder="Buscar rol…">
        </div>

    </div>

    <!-- TABLA -->
     <div class="card p-2">

    <div class="table-responsive">
        <table id="tablaRoles" class="table table-light table-hover align-middle mb-0">
            <thead class="text-dark table-success p-2">
                <tr>
                    <th><i class="bi bi-hash"></i> ID</th>
                    <th><i class="bi bi-shield-lock-fill"></i> Rol</th>
                    <th><i class="bi bi-gear-fill"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($roles)): ?>
                    <?php foreach ($roles as $rol): ?>
                        <tr>
                            <td data-label="ID">
                                <i class="bi bi-hash"></i> <?= htmlspecialchars($rol["id"]) ?>
                            </td>
                            <td data-label="Rol">
                                <i class="bi bi-person-badge-fill"></i> <?= htmlspecialchars($rol["nombre"]) ?>
                            </td>
                            <td data-label="Acciones">
                                <a href="editar_rol.php?id=<?= urlencode($rol["id"]) ?>" class="btn btn-sm btn-outline-info"
                                    title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-white text-center">No se encontraron resultados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
        
    </div>
</div>