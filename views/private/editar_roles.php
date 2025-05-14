<?php

// Verifica si el usuario está autenticado
if (empty($_SESSION['usuario'])) {
    $_SESSION['alerta'] = "Debes iniciar sesión para acceder a esta sección.";
    header("Location: login.php");
    exit;
}

// Validar ID recibido por GET
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    $_SESSION['alerta'] = 'ID no válido o ausente.';
    header("Location: index.php?vista=roles");
    exit;
}

// Buscar el rol en la base de datos
$stmt = $pdo->prepare("SELECT * FROM roles WHERE id = ?");
$stmt->execute([$id]);
$rol = $stmt->fetch(PDO::FETCH_ASSOC);

// Validar existencia del rol
if (!$rol) {
    $_SESSION['alerta'] = 'No existe un rol con el ID especificado.';
    header("Location: index.php?vista=roles");
    exit;
}

// Aquí continúa tu lógica si el rol existe...

?>
<div id="content" class="container container-fluid-ms  py-4">
    <div class="card border-0 shadow rounded-4 col-lg-9 mx-auto">
        <div class="card-header bg-warning text-dark rounded-top-4 py-3">
            <h5 class="mb-0 text-white">
                <i class="bi bi-person-vcard-fill fs-4 me-2"></i>
                Editar Rol
            </h5>
        </div>

        <div class="card-body px-4 py-4">
            <form id="form" method="POST" class="row g-4 needs-validation" novalidate>
<input type="hidden" id="id" name="id" value="<?= htmlspecialchars(string: $rol['id']) ?>">
                <div class="col-md-12">
                    <label for="nombre" class="form-label">
                        <i class="bi bi-card-text me-1 text-primary"></i> Nombre del rol <span
                            class="text-danger">*</span>
                    </label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="<?= htmlspecialchars($rol['nombre']) ?>" required>
                </div>


                <div class="col-12 d-flex justify-content-between pt-3">
                    <a href="index.php?vista=roles" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save-fill me-1"></i> Guardar
                    </button>
                </div>

            </form>


        </div>
    </div>
</div>


<script>
    const form = document.getElementById('form');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        try {
            const response = await fetch('api/actualizar_roles.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
         
                alert(result.message)
                window.location.href = 'index.php?vista=roles';
            } else {
                console.error('Error:', result.message);
                // Errores
                alert(result.message) 
            }

        } catch (error) {
            console.error('Error:', error);
           
        }
    });

</script>