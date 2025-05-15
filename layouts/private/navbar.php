<!-- Topbar -->
<nav id="navbar" class="navbar position-fixed navbar-expand navbar-dark shadow-sm px-3">
    <button id="toggleSidebar" class="btn btn-outline-dark me-3">
        <i class="bi bi-list"></i>
    </button>
    <span class="navbar-brand mb-0 h5 text-dark"><i class="bi bi-hammer me-2"></i>Panel Carpintería</span>
    <div class="ms-auto user-info">
        <a href="index.php?vista=inicio" class="btn btn-sm btn-outline-primary ms-2">
            <i class="bi bi-house-door me-2"></i> Salir
        </a>
       <!--  <img src="#" alt="Avatar usuario"> -->
        <div>
            <div class="fw-bold"><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></div>
            <small> <?= htmlspecialchars($_SESSION['usuario']['rol']) ?> </small>
        </div>
        <a href="logout.php" class="btn btn-sm btn-outline-danger ms-2">
            <i class="bi bi-box-arrow-right me-2"></i> Salir
        </a>
    </div>
</nav>
<!-- Toast container global -->
<div id="toast-container"></div>