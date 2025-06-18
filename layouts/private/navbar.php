  <div class="wrapper">
<nav id="navbar" class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3 position-fixed " style="z-index: 1030;">
    <div class="container-fluid">
        <!-- Botón toggle sidebar -->
        <button id="toggleSidebar" class="btn btn-outline-dark me-2" aria-label="Menú lateral">
            <i class="bi bi-list fs-5"></i>
        </button>

        <!-- Logo + usuario -->
        <span class="navbar-brand d-flex align-items-center fw-semibold text-dark mb-0 text-truncate">
            <i class="bi bi-hammer me-2 fs-4"></i>
            <span class="d-none d-md-inline">
                Panel <?= htmlspecialchars($_SESSION['usuario']['rol']) ?>: <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
            </span>
            <span class="d-inline d-md-none">Panel</span>
        </span>

        <!-- Toggle colapso -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-three-dots-vertical"></i>
        </button>

        <!-- Contenido -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
            <div class="d-flex align-items-center flex-wrap gap-2 mt-2 mt-lg-0">
                <!-- Botón Home -->
                <a href="index.php?vista=inicio"
                    class="btn btn-sm btn-outline-primary d-flex align-items-center home-btn">
                    <i class="bi bi-house-door"></i>
                    <span class="ms-1 d-none d-sm-inline">Home</span>
                </a>

                <!-- Info usuario (solo visible en md+) -->
                <div class="text-end d-none d-md-block me-2">
                    <div class="fw-bold text-dark"><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></div>
                    <small class="text-muted"><?= htmlspecialchars($_SESSION['usuario']['rol']) ?></small>
                </div>

                <!-- Notificaciones -->
                <?php if ($_SESSION['usuario']['rol'] === 'Administrador'): ?>
                    <button id="btnNotificaciones" class="btn btn-sm btn-outline-warning position-relative noti-btn" aria-label="Notificaciones">
                        <i class="bi bi-bell-fill"></i>
                        <span id="contadorNotificaciones" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            0
                        </span>
                        <span class="ms-1 d-none d-sm-inline">Avisos</span>
                    </button>
                <?php endif; ?>

                <!-- Logout -->
                <button id="cerrarSession" class="btn btn-sm btn-outline-danger d-flex align-items-center logout-btn">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="ms-1 d-none d-sm-inline">Salir</span>
                </button>
            </div>
        </div>
    </div>
</nav>


<!-- Toast container global -->
<div id="toast-container"></div>