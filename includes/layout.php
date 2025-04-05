<!-- layout.php -->
<?php include_once("../includes/header.php"); ?>
<?php include_once("../includes/nav.php"); ?>

<div class="d-flex">
  <!-- Sidebar -->
  <?php include_once("../includes/sidebar.php"); ?>

  <!-- Contenido principal -->
  <div class="flex-grow-1 p-4">
    <?php if (isset($titulo_pagina)): ?>
      <h3 class="mb-4"><?= htmlspecialchars($titulo_pagina) ?></h3>
    <?php endif; ?>
    
    <!-- Aquí se renderiza el contenido dinámico -->
    <?php if (isset($contenido)): ?>
      <?= $contenido ?>
    <?php endif; ?>
  </div>
</div>

<?php include_once("../includes/footer.php"); ?>
