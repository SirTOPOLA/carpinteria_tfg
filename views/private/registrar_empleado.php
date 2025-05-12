 





<div id="content" class="container-fluid py-4">
        <h4 class="mb-3">Registrar Empleado</h4>

       
        <form id="form"  method="POST" class="row g-3">
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
            <div class="col-12  d-flex justify-content-between">
                <a href="index.php?vista=empleados" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>
 

    <script>
document.querySelector('form').addEventListener('submit', async function (e) {
    e.preventDefault(); // Evita el envío tradicional del formulario

    const form = e.target;
    const formData = new FormData(form);

    try {
        const response = await fetch('api/guardar_empleado.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert('Empleado registrado correctamente');
            window.location.href = 'index.php?vista=empleados'; // redirige al listado
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error en la solicitud:', error);
        alert('Ocurrió un error al guardar el empleado.');
    }
});
</script>
