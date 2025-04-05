
<?php 

$usuario_logueado = "Admin Carpintería";
?>

<nav class="navbar navbar-dark bg-dark px-4 justify-content-between">
  <span class="navbar-brand mb-0 h5">
      <i class="bi bi-hammer"></i> Sistema de Gestión
  </span>
  
  <div class="d-flex align-items-center">
      <span class="text-white me-3">
          <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($usuario_logueado) ?>
        </span>
        <a href="../includes/logout.php" class="btn btn-sm btn-outline-light">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
        </a>
    </div>
</nav>
