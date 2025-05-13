<?php
 

// Validar ID del proyecto
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?vista=proyectos");
    exit;
}

$id = (int) $_GET['id'];
$mensaje = "";

// Obtener datos actuales del proyecto
$sql = "SELECT * FROM proyectos WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$proyecto = $stmt->fetch(PDO::FETCH_ASSOC);
 
 

?>
 
<div id="content" class="container-fluid py-4">
        <h4 class="mb-4">Editar Proyecto</h4>



        <form id="form" method="POST" class="row g-3 needs-validation" novalidate>
            <input type="hidden" class="form-control" name='proyecto_id' value="<?= htmlspecialchars($proyecto['id']) ?>" >
            <div class=" col-12 col-md-6">
                <label for="nombre" class="form-label">Nombre del Proyecto</label>
                <input type="text" name="nombre" id="nombre" class="form-control"
                    value="<?= htmlspecialchars($proyecto['nombre']) ?>" required>
            </div>
            <div class=" col-12 col-md-6">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select" required>
                    <option value="<?= htmlspecialchars($proyecto['estado']) ?>"><?= htmlspecialchars($proyecto['estado']) ?></option>
                    <option value="">seleccione un estado..</option>
                    <option value="pendiente">pendiente</option>
                    <option value="en diseño">en diseño</option>
                    <option value="En producción">En producción</option>
                    <option value="Finalizado">Finalizado</option>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                <input type="text" name="fecha_inicio" value=" <?= htmlspecialchars($proyecto['fecha_inicio']) ?> "
                    id="fecha_inicio" class="form-control" disabled>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
            </div>

            <div class="col-12 col-md-6">
                <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                <input type="text" name="fecha_entrega" value=" <?= htmlspecialchars($proyecto['fecha_entrega']) ?> "
                    id="fecha_entrega" class="form-control" disabled>
                <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control">
            </div>


            <div class=" col-12 col-md-6">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion"
                    class="form-control"><?= htmlspecialchars($proyecto['descripcion']) ?></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <a href="index.php?vista=proyectos" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
 
 