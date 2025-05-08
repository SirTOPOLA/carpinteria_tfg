

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
    <div class="container">
        <h4 class="mb-4">Registrar Proyecto</h4>
 

        <form action="../php/guardar_proyectos.php" method="POST" class="row g-3 needs-validation" novalidate>
            <div class="col-12 col-md-6">
                <label for="nombre" class="form-label">Nombre del Proyecto</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div> 
           

            <div class="col-12 col-md-6">
                <label for="estado" class="form-label">Estado actual del proyecto</label>
                <select name="estado" id="estado" class="form-select" required>
                    <option value="">seleccione un estado..</option>
                    <option value="pendiente">pendiente</option>
                    <option value="en diseño">en diseño</option>
                    <option value="En producción">En producción</option>
                    <option value="Finalizado">Finalizado</option>
                </select>
            </div> 
            <div class="col-12 col-md-6">
                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
            </div>

            <div class="col-12 col-md-6">
                <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control">
            </div>

            <div class="col-12 col-md-6">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="4"></textarea>
            </div>

            <div class="col-12 text-end">
                <a href="proyectos.php" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Registrar Proyecto</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
