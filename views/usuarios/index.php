<?php require "views/layouts/header.php"; ?>

<div class="dashboard">

    <?php require "views/layouts/sidebar.php"; ?>

    <main class="main-content">

        <h3>Usuarios</h3>

        <a href="?page=usuarioCrear" class="btn btn-primary mb-3">
            Nuevo Usuario
        </a>

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Empleado</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($usuarios as $u): ?>

                    <tr>

                        <td><?= $u["id_usuario"] ?></td>

                        <td><?= $u["username"] ?></td>

                        <td><?= $u["rol"] ?></td>

                        <td><?= $u["empleado"] ?></td>

                        <td>
                            <?= $u["activo"] ? "Activo" : "Inactivo" ?>
                        </td>

                        <td>

                            <a href="?page=usuarioEditar&id=<?= $u["id_usuario"] ?>" class="btn btn-warning btn-sm">
                                Editar
                            </a>

                            <a href="?page=usuarioEliminar&id=<?= $u["id_usuario"] ?>" class="btn btn-danger btn-sm">
                                Eliminar
                            </a>

                        </td>

                    </tr>

                <?php endforeach ?>

            </tbody>

        </table>

    </main>
</div>
 
<?php require "views/layouts/footer.php"; ?>