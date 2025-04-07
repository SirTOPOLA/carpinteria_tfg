<?php
// dashboard.php principal
include '../includes/conexion.php'; // Conexión a base de datos
include '../includes/header.php';
include '../includes/nav.php';
include '../includes/sidebar.php';

 ?>

<main class="flex-grow-1 overflow-auto p-3" id="mainContent">
  <div class="container-fluid">
     
    <?php include 'inventario_general.php'; ?>
     

  </div>
</main>

<?php include '../includes/footer.php'; ?>

<!-- Scripts de gráficos -->
 