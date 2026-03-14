<div class="container mt-4">

    <h3>Crear Usuario</h3>

    <form method="POST" action="?page=usuarioGuardar">

        <div class="mb-3">
            <label>Empleado</label>
            <input type="number" name="empleado" class="form-control">
        </div>

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Rol</label>
            <input type="number" name="rol" class="form-control" required>
        </div>

        <button class="btn btn-success">
            Guardar
        </button>

    </form>

</div>