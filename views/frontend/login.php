<?php require "views/layouts/header.php";

$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);

?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg border-0 rounded-4 p-4" style="max-width: 420px; width: 100%;">
        
        <!-- ENCABEZADO PERSUASIVO (Cialdini: Autoridad + Confianza) -->
        <div class="text-center mb-3">
            <i class="bi bi-shield-lock-fill text-primary" style="font-size: 3rem;"></i>
            <h4 class="fw-bold mt-2">Acceso Seguro</h4>
            <p class="text-muted small">
                Panel exclusivo del sistema de gestión profesional.
            </p>
        </div>

        <!-- Prueba social / Confianza -->
        <div class="alert alert-info small py-2 text-center">
            <i class="bi bi-people-fill"></i> Más de 20 usuarios usan este sistema diariamente.
        </div>

        <?php if (!empty($msg)): ?>
        <div class="alert alert-danger small text-center">
            <i class="bi bi-exclamation-octagon-fill"></i> <?= htmlspecialchars($msg) ?>
        </div>
        <?php endif; ?>

        <form action="<?= urlsite ?>?page=loginAuth" method="post" class="needs-validation" novalidate>
            
            <!-- Usuario -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Usuario</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person-fill"></i>
                    </span>
                    <input type="text" name="username" class="form-control" required placeholder="Ingrese su usuario">
                    <div class="invalid-feedback">El usuario es obligatorio.</div>
                </div>
            </div>

            <!-- Contraseña -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                    <input type="password" name="password_hash" id="password" class="form-control" required placeholder="Ingrese su contraseña">
                    <button type="button" class="btn btn-outline-secondary" id="togglePass">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                    <div class="invalid-feedback">La contraseña es obligatoria.</div>
                </div>
            </div>

            <!-- Botón principal persuasivo (Cialdini: Compromiso + Coherencia) -->
            <div class="d-grid mt-3">
                <button type="submit" class="btn btn-primary py-2 fw-bold">
                    <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
                </button>
            </div>

            <!-- Mensaje persuasivo (Cialdini: Escasez/Oportunidad) -->
            <p class="text-center mt-3 text-muted small">
                Acceso exclusivo para personal autorizado.  
                <strong>Tu sesión es monitoreada para mayor seguridad.</strong>
            </p>

        </form>

        <!-- CTA secundario -->
        <div class="text-center mt-3">
            <a href="<?= urlsite ?>?page=home" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-house-door-fill"></i> Ir al inicio
            </a>
        </div>

    </div>
</div>
<script src="public/js/login.js"></script>
<?php require "views/layouts/footer.php"; ?>
