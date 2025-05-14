<?php

// Si no hay sesión → redirige a login
if (!isset($_SESSION['usuario'])) {
    $_SESSION['alerta'] = "Debes registrarte para continuar con esta petición.";
    header("Location: login.php");
    exit;
}

// Validar ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    $_SESSION['alerta'] = 'ID de cliente no válido.';
    header('Location: index.php?vista=clientes');
    exit;
}

// Consultar cliente
try {
    $sql = "SELECT * FROM clientes WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        $_SESSION['alerta'] = 'Cliente no encontrado.';
        header('Location: clientes.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['alerta'] = 'Error al consultar el cliente.';
    header('Location: index.php?vista=clientes');
    exit;
}
?>
<!-- Contenido -->
<div id="content" class="container-fluid py-4">
    <div class="container-fluid container-md px-4 px-sm-3 px-md-4">
        <div class="card border-0 shadow rounded-4 col-lg-8 mx-auto">
            <div class="card-header bg-info text-white rounded-top-4 py-3">
                <h5 class="mb-0">
                    <i class="bi bi-person-lines-fill me-2"></i>Editar Cliente
                </h5>
            </div>

            <div class="card-body">
                <form id="formEditarCliente"  class="row g-3 needs-validation" novalidate>
                    <input type="hidden" name="id" id="id" value="<?= htmlspecialchars($cliente['id']) ?> ">
                    <!-- Nombre completo -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre completo <span
                                class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input type="text" name="nombre" id="nombre" class="form-control" required
                                value="<?= htmlspecialchars($cliente['nombre']) ?>">
                            <div class="invalid-feedback">El nombre es obligatorio.</div>
                        </div>
                    </div>

                    <!-- Correo -->
                    <div class="col-md-6">
                        <label for="correo" class="form-label">Correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                            <input type="email" name="correo" id="correo" class="form-control"
                                value="<?= htmlspecialchars($cliente['email']) ?>">
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                            <input type="text" name="telefono" id="telefono" class="form-control"
                                value="<?= htmlspecialchars($cliente['telefono']) ?>">
                        </div>
                    </div>
                     <!-- DIP* -->
                     <div class="col-md-6">
                        <label for="codigo" class="form-label">DIP*</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                            <input type="text" name="codigo" id="codigo" class="form-control"
                            value="<?= htmlspecialchars($cliente['codigo']) ?>">
                        </div>
                    </div>
      

                    <!-- Dirección -->
                    <div class="col-md-6">
                        <label for="direccion" class="form-label">Dirección</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                            <textarea name="direccion" id="direccion" class="form-control"
                                rows="1"><?= htmlspecialchars($cliente['direccion']) ?></textarea>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="col-12 d-flex justify-content-between mt-3">
                        <a href="index.php?vista=clientes" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left-circle me-1"></i>Volver
                        </a>
                        <button type="submit" class="btn btn-outline-warning text-dark rounded-pill px-4">
                            <i class="bi bi-save2-fill me-1"></i>Actualizar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formEditarCliente');

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
                const response = await fetch('api/actualizar_clientes.php', {
                    method: 'POST',
                    body: formData
                });

                const resultado = await response.json();

                if (resultado.success) {
                    alert('Cliente registrado con éxito.');
                    form.reset();
                    form.classList.remove('was-validated');
                } else {
                    alert(resultado.error || 'Error al registrar el cliente.');
                }

            } catch (error) {
                console.error('Error en el envío:', error);
                alert('Error de conexión. Inténtalo de nuevo.');
            }
        });
    });
</script>