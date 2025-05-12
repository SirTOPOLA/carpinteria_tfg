<?php
require_once("../includes/conexion.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: empleados.php");
    exit;
}

// Obtener datos del empleado
$stmt = $pdo->prepare("SELECT * FROM empleados WHERE id = ?");
$stmt->execute([$id]);
$empleado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$empleado) {
    header("Location: registrar_empleado.php");
    exit;
}

  
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/nav.php'; ?>
<?php include '../includes/sidebar.php'; ?>

   <!-- Contenido -->
   <div class="container-fluid py-4">
    <div class="container">
        <h4>Editar Empleado</h4>
        <form id="formEditarEmpleado" method="POST" class="row g-3">
         <!-- ID oculto -->
         <input type="hidden" name="id" value="<?= htmlspecialchars($empleado['id']) ?>">
   
        <div class="col-12 col-md-6">
                <label class="form-label">Nombre *</label>
                <input type="text" required  value="<?= htmlspecialchars($empleado['nombre']) ?>"  name="nombre" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Apellido</label>
                <input type="text" required  value="<?= htmlspecialchars($empleado['apellido']) ?>"  name="apellido" class="form-control">
            </div>
            <div class="col-12 col-md-6"> 
                <label for="genero" class="form-label">genero</label>
                <select required   name="genero" id="genero" class="form-select" required>
                     <option value="<?= htmlspecialchars($empleado['genero']);?>" ><?= htmlspecialchars($empleado['genero']) == "M" ? 'Mujer' : 'Hombre' ?></option>
                    <option value="M">Hombre</option>
                    <option value="F">Mujer</option>
                </select>
            </div>
            
             <div class="col-12 col-md-6">
                <label class="form-label">fecha_nacimiento </label>
                <input type="date" required  value="<?= htmlspecialchars($empleado['fecha_nacimiento']) ?>"  name="fecha_nacimiento" value="<?= htmlspecialchars($empleado['fecha_nacimiento']) ?>" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="text" required  value="<?= htmlspecialchars($empleado['telefono']) ?>"  name="telefono"   class="form-control">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Email</label>
                <input type="email" required  value="<?= htmlspecialchars($empleado['email']) ?>"  name="email" class="form-control">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Dirección</label>
                <input type="text" required  value="<?= htmlspecialchars($empleado['direccion']) ?>"  name="direccion" class="form-control">
            </div> 
            <div class="col-12 col-md-6">
                <label class="form-label">Fecha de contrato</label>
                <input type="date" required  value="<?= htmlspecialchars($empleado['fecha_ingreso']) ?>"  name="fecha_ingreso" class="form-control">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Horario de Trabajo</label>
                <input type="text" required  value="<?= htmlspecialchars($empleado['horario_trabajo']) ?>"  name="horario_trabajo" class="form-control"
                    placeholder="Ej: Lunes a Viernes, 8am - 5pm">
            </div>
            <div class="col-12  text-end">
                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="empleados.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>


        
    </div>
</div>


<script>
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<script>
document.getElementById('formEditarEmpleado').addEventListener('submit', function(e) {
    e.preventDefault();

    const empleado = {
        id: document.getElementById('empleado_id').value,
        nombre: document.getElementById('nombre').value,
        apellido: document.getElementById('apellido').value,
        codigo: document.getElementById('codigo').value,
        email: document.getElementById('email').value,
        telefono: document.getElementById('telefono').value,
        direccion: document.getElementById('direccion').value,
        horario_trabajo: document.getElementById('horario_trabajo').value,
        salario: document.getElementById('salario').value,
        fecha_ingreso: document.getElementById('fecha_ingreso').value
    };

    fetch('api/editar_empleado.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(empleado)
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) {
            alert('Empleado guardado exitosamente.');
            window.location.href = 'index.php?vista=empleados';
        } else {
            alert('Error: ' + data.mensaje);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error al enviar datos');
    });
});
</script>

