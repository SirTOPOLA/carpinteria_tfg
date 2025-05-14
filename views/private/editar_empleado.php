<?php

// Validar ID recibido
$_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($_id <= 0) {
    $_SESSION['alerta'] = "ID proporcionado no válido";
    header("Location: index.php?vista=empleados");
    exit;
}

// Validar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    $_SESSION['alerta'] = "Debes iniciar sesión para continuar.";
    header("Location: login.php");
    exit;
}

// Consulta al empleado
$sql = "SELECT * FROM empleados WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_id]);
$empleado = $stmt->fetch(PDO::FETCH_ASSOC);

// Validar si existe
if (!$empleado) {
    $_SESSION['alerta'] = "No existe un registro para el ID proporcionado.";
    header("Location: index.php");
    exit;
}

?>

<!-- Contenido -->
<div id="content" class="container-fluid py-4">

    <div class="container-fluid container-md px-4 px-sm-3 px-md-4">
        <div class="card border-0 shadow rounded-4">
            <div class="card-header bg-warning text-dark rounded-top-4 py-3">
                <h5 class="mb-0 text-white">
                    <i class="bi bi-pencil-square me-2"></i>Editar Empleado
                </h5>
            </div>

            <div class="card-body">
                <form id="form" method="POST" class="row g-3 needs-validation" novalidate>
                    <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($empleado['id']) ?>">
                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label class="form-label">Nombre *</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input type="text" name="nombre" id="nombre"
                                value="<?= htmlspecialchars($empleado['nombre']) ?>" class="form-control" required>
                            <div class="invalid-feedback">El nombre es obligatorio.</div>
                        </div>
                    </div>

                    <!-- Apellido -->
                    <div class="col-md-6">
                        <label class="form-label">Apellido</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-lines-fill"></i></span>
                            <input type="text" name="apellido" id="apellido"
                                value="<?= htmlspecialchars($empleado['apellido']) ?>" class="form-control">
                        </div>
                    </div>

                    <!-- Género -->
                    <div class="col-md-6">
                        <label for="genero" class="form-label">Género *</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                            <select name="genero" id="genero" class="form-select" required>
                                <option value="">Seleccione el género</option>
                                <option value="M" <?= $empleado['genero'] == 'M' ? 'selected' : '' ?>>Hombre</option>
                                <option value="F" <?= $empleado['genero'] == 'F' ? 'selected' : '' ?>>Mujer</option>
                            </select>
                            <div class="invalid-feedback">Debe seleccionar un género.</div>
                        </div>
                    </div>

                    <!-- Fecha de nacimiento -->
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Nacimiento *</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= $empleado['fecha_nacimiento'] ?>"
                                class="form-control" required>
                            <div class="invalid-feedback">La fecha de nacimiento es requerida.</div>
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                            <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($empleado['telefono']) ?>"
                                class="form-control">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                            <input type="email" id="email" name="
                            email" value="<?= htmlspecialchars($empleado['email']) ?>"
                                class="form-control">
                        </div>
                    </div>
                    <!-- DIP* -->
                    <div class="col-md-6">
                        <label for="codigo" class="form-label">DIP*</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                            <input type="text" name="codigo" id="codigo" class="form-control" value="<?= htmlspecialchars($empleado['codigo']) ?>">
                        </div>
                    </div>
                    <!-- Dirección -->
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                            <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($empleado['direccion']) ?>"
                                class="form-control">
                        </div>
                    </div>

                    <!-- Fecha de contrato -->
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Contrato</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar-check-fill"></i></span>
                            <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="<?= $empleado['fecha_ingreso'] ?>"
                                class="form-control">
                        </div>
                    </div>

                    <!-- Horario de trabajo -->
                    <div class="col-md-6">
                        <label class="form-label">Horario de Trabajo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-clock-fill"></i></span>
                            <input type="text" id="horario_trabajo" name="horario_trabajo"
                                value="<?= htmlspecialchars($empleado['horario_trabajo']) ?>" class="form-control"
                                >
                        </div>
                    </div>

                    <!-- Salario con moneda -->
                    <div class="col-md-6">
                        <label class="form-label">Salario (opcional)</label>
                        <div class="input-group">
                            <select id="moneda" name="moneda" class="form-select" style="max-width: 100px;">
                                <option value="XAF" >FCFA</option>
                                <option value="USD" >$</option>
                                <option value="EUR" >€</option>
                            </select>
                            <input type="text" id="salario" name="salario" value="<?= htmlspecialchars(number_format((float) $empleado['salario'], 2, '.', '')) ?>"
                            class="form-control">
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="col-12 d-flex justify-content-between mt-3">
                        <a href="index.php?vista=empleados" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left-circle me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning text-dark rounded-pill px-4">
                            <i class="bi bi-check-circle-fill me-1"></i>Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>



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
        document.querySelector('form').addEventListener('submit', async function (e) {
        e.preventDefault(); // Evita el envío tradicional del formulario

        const form = e.target;
        const formData = new FormData(form);

        try {
            const response = await fetch('api/actualizar_empleado.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert('Error: ' + result.message);
               // alert('Empleado registrado correctamente');
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