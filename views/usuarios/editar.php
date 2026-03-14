<div class="container mt-4">

    <h3>Editar Usuario</h3>

    <form method="POST" action="?page=usuarioActualizar">

        <input type="hidden" name="id" value="<?= $usuario["id_usuario"] ?>">

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" value="<?= $usuario["username"] ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label>Rol</label>
            <input type="number" name="rol" value="<?= $usuario["id_rol"] ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label>Estado</label>

            <select name="activo" class="form-control">

                <option value="1" <?= $usuario["activo"] ? "selected" : "" ?>>
                    Activo
                </option>

                <option value="0" <?= !$usuario["activo"] ? "selected" : "" ?>>
                    Inactivo
                </option>

            </select>

        </div>

        <button class="btn btn-primary">
            Actualizar
        </button>

    </form>

</div>