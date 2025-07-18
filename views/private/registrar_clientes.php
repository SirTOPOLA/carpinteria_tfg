<?php

// Si no hay sesión → redirige a login
if (!isset($_SESSION['usuario'])) {
    $_SESSION['alerta'] = "Debes registrarte para continuar con esta petición.";
    header("Location: login.php");
    exit;
}
?>


<div id="content" class="container-fliud">
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-warning text-dark rounded-top-4 py-3">
            <h5 class="mb-0 text-white">
                <i class="bi bi-person-badge fs-4 me-2"></i>
                Registrar Cliente
            </h5>
        </div>




        <div class="card-body">
            <form id="formRegistarCliente" method="POST" class="row g-3 needs-validation" novalidate>

                <!-- Nombre completo -->
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre completo <span class="text-danger">*</span></label>
                    <div class="input-group has-validation">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                        <div class="invalid-feedback">El nombre es obligatorio.</div>
                    </div>
                </div>

                <!-- Correo -->
                <div class="col-md-6">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" name="correo" id="correo" class="form-control" value=" ">
                    </div>
                </div>

                <!-- Teléfono -->
                <div class="col-md-6">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                        <input type="text" name="telefono" id="telefono" class="form-control" value=" ">
                    </div>
                </div>
                <!-- DIP* -->
                <div class="col-md-6">
                    <label for="codigo" class="form-label">DIP*</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                        <input type="text" name="codigo" id="codigo" class="form-control" value=" ">
                    </div>
                </div>

                <!-- Dirección -->
                <div class="col-md-6">
                    <label for="direccion" class="form-label">Dirección</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                        <textarea name="direccion" id="direccion" class="form-control" rows="1"> </textarea>
                    </div>
                </div>

                <!-- Botones -->
                <div class="col-12 d-flex justify-content-between mt-3">
                    <a href="index.php?vista=clientes" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left-circle me-1"></i>Volver
                    </a>
                    <button type="submit" class="btn btn-outline-success text-outline-dark rounded-pill px-4">
                        <i class="bi bi-save2-fill me-1"></i>Guardar
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

 








<!-- Autorelleno de los datos provinientes del logs.txt notificacion -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const cliente = JSON.parse(localStorage.getItem("clienteTemporal"));
        if (cliente) {
            if (document.getElementById("nombre")) {
                document.getElementById("nombre").value = cliente.nombre || "";
                document.getElementById("correo").value = cliente.correo || "";
                document.getElementById("telefono").value = cliente.telefono || "";
                document.getElementById("codigo").value = cliente.codigo || "";
                document.getElementById("direccion").value = cliente.direccion || "";
            }

            // Limpiar storage para no rellenar en la próxima visita
            localStorage.removeItem("clienteTemporal");
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formRegistarCliente');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Validación con clases de Bootstrap 5
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            // Reunir los datos del formulario
            const formData = new FormData(form);

            try {
                const response = await fetch('api/guardar_clientes.php', {
                    method: 'POST',
                    body: formData
                });

                const resultado = await response.json();

                if (resultado.ok) {
                    alert('Cliente registrado con éxito.');
                    form.reset();
                    form.classList.remove('was-validated');
                    window.location.href = 'index.php?vista=clientes';
                } else {
                    alert(resultado.mensaje || 'Error al registrar el cliente.');
                    //  form.reset();
                    //  form.classList.remove('was-validated');
                }

            } catch (error) {
                console.error('Error en el envío:', error);
                alert('Error de conexión. Inténtalo de nuevo.');
            }
        });
    });
</script>