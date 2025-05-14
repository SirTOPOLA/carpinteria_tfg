<?php
// Si no hay sesión → redirige a login
if (!isset($_SESSION['usuario'])) {
    $_SESSION['alerta'] = "Debes registrarte para continuar con esta petición.";
    header("Location: login.php");
    exit;
}
?>

<div id="content" class="container-fluid py-4">
    <div class="container-fluid container-md px-4 px-sm-3 px-md-4">
        <div class="card border-0 shadow rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4 py-3">
                <h5 class="mb-0">
                    <i class="bi bi-person-plus-fill me-2"></i>Registrar Empleado
                </h5>
            </div>

            <div class="card-body">
                <form id="form" method="POST" class="row g-3 needs-validation" novalidate>

                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label class="form-label">Nombre *</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input type="text" name="nombre" class="form-control" required>
                            <div class="invalid-feedback">El nombre es obligatorio.</div>
                        </div>
                    </div>

                    <!-- Apellido -->
                    <div class="col-md-6">
                        <label class="form-label">Apellido</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-lines-fill"></i></span>
                            <input type="text" name="apellido" class="form-control">
                        </div>
                    </div>

                    <!-- Género -->
                    <div class="col-md-6">
                        <label for="genero" class="form-label">Género *</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                            <select name="genero" id="genero" class="form-select" required>
                                <option value="">Seleccione el género</option>
                                <option value="Masculino">Hombre</option>
                                <option value="Femenino">Mujer</option>
                            </select>
                            <div class="invalid-feedback">Debe seleccionar un género.</div>
                        </div>
                    </div>

                    <!-- Fecha de nacimiento -->
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Nacimiento *</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                            <input type="date" name="fecha_nacimiento" class="form-control" required>
                            <div class="invalid-feedback">La fecha de nacimiento es requerida.</div>
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                            <input type="text" name="direccion" class="form-control">
                        </div>
                    </div>

                    <!-- Fecha de contrato -->
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Contrato</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar-check-fill"></i></span>
                            <input type="date" name="fecha_ingreso" class="form-control">
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
                    <!-- Horario de trabajo -->
                    <div class="col-md-6">
                        <label class="form-label">Horario de Trabajo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-clock-fill"></i></span>
                            <input type="text" name="horario_trabajo" class="form-control"
                                placeholder="Ej: Lunes a Viernes, 8am - 5pm">
                        </div>
                    </div>

                    <!-- Salario con moneda -->
                    <div class="col-md-6">
                        <label class="form-label">Salario (opcional)</label>
                        <div class="input-group">
                            <select id="moneda" class="form-select" style="max-width: 100px;">
                                <option value="XAF">FCFA</option>
                                <option value="USD">$</option>
                                <option value="EUR">€</option>
                            </select>
                            <input type="text" name="salario" id="salario" class="form-control" placeholder="Ej: 1500.00">
                        </div>
                    </div>

                    <!-- Botones -->

                    <div class="col-12 d-flex justify-content-between pt-3">
                        <a href="index.php?vista=empleados" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left-circle me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-outline-success rounded-pill px-4">
                            <i class="bi bi-save-fill me-1"></i>Registrar
                        </button>

                    </div>

                </form>
            </div>
        </div>
    </div>

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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const salarioInput = document.getElementById("salario");
        const monedaSelect = document.getElementById("moneda");
        const form = document.getElementById("form");

        function formatMoneda(valor, currency) {
            if (valor === '') return '';
            const numero = parseFloat(valor.replace(/[^0-9.,]/g, '').replace(',', '.'));
            if (isNaN(numero)) return '';

            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: currency,
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(numero);
        }

        function limpiarFormato(valor) {
            return valor.replace(/[^0-9,\.]/g, '').replace(',', '.');
        }

        function actualizarFormato() {
            let valor = salarioInput.value;
            valor = limpiarFormato(valor);
            salarioInput.value = formatMoneda(valor, monedaSelect.value);
        }

        salarioInput.addEventListener('input', actualizarFormato);
        monedaSelect.addEventListener('change', actualizarFormato);

        form.addEventListener('submit', function () {
            let limpio = limpiarFormato(salarioInput.value);
            salarioInput.value = parseFloat(limpio).toFixed(2);
        });
    });
</script>