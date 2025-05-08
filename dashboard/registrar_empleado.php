

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container">
        <h4 class="mb-3">Registrar Empleado</h4>

        <?php if (!empty($exito)): ?>
            <div class="alert alert-success"><?= $exito ?></div>
        <?php endif; ?>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errores as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?> 
        <form action="../php/guardar_empleados.php" method="POST" class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control">
            </div>
            <div class="col-12 col-md-6"> 
                <label for="genero" class="form-label">genero</label>
                <select name="genero" id="genero" class="form-select" required>
                     <option  >seleccione el genero</option>
                    <option value="M">Hombre</option>
                    <option value="F">Mujer</option>
                </select>
            </div>
            
             <div class="col-12 col-md-6">
                <label class="form-label">fecha_nacimiento </label>
                <input type="date" name="fecha_nacimiento" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control">
            </div>

            <!-- <div class="col-12 col-md-6">
                <label class="form-label">Salario</label>
                <input type="number" name="salario" step="0.01" min="0" class="form-control">
            </div> -->
            <div class="col-12 col-md-6">
                <label class="form-label">Fecha de contrato</label>
                <input type="date" name="fecha_ingreso" class="form-control">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Horario de Trabajo</label>
                <input type="text" name="horario_trabajo" class="form-control"
                    placeholder="Ej: Lunes a Viernes, 8am - 5pm">
            </div>
            <div class="col-12  text-end">
                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="empleados.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>