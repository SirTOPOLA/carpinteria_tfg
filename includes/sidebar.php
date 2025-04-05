<?php
// Detectamos el archivo actual (ej: 'usuarios.php')
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar bg-dark text-white p-3" style="width: 250px; min-height: 100vh;">
  <h4 class="text-center mb-4 border-bottom pb-2">🪚 Carpintería</h4>

  <nav class="nav flex-column">
    <a href="index.php" class="nav-link text-white py-2 px-3 rounded <?= $pagina_actual === 'dashboard_admin.php' ? 'bg-primary' : 'hover-bg' ?>">
      <i class="bi bi-house me-2"></i> Inicio
    </a>
    <a href="usuarios.php" class="nav-link text-white py-2 px-3 rounded <?= $pagina_actual === 'usuarios.php' ? 'bg-primary' : 'hover-bg' ?>">
      <i class="bi bi-people-fill me-2"></i> Usuarios
    </a>
    <a href="clientes.php" class="nav-link text-white py-2 px-3 rounded <?= $pagina_actual === 'clientes.php' ? 'bg-primary' : 'hover-bg' ?>">
      <i class="bi bi-people me-2"></i> Clientes
    </a>
    <a href="productos.php" class="nav-link text-white py-2 px-3 rounded <?= $pagina_actual === 'productos.php' ? 'bg-primary' : 'hover-bg' ?>">
      <i class="bi bi-box-seam me-2"></i> Productos
    </a>
    <a href="proveedores.php" class="nav-link text-white py-2 px-3 rounded <?= $pagina_actual === 'proveedores.php' ? 'bg-primary' : 'hover-bg' ?>">
      <i class="bi bi-truck me-2"></i> Proveedores
    </a>
    <a href="ventas.php" class="nav-link text-white py-2 px-3 rounded <?= $pagina_actual === 'ventas.php' ? 'bg-primary' : 'hover-bg' ?>">
      <i class="bi bi-cash me-2"></i> Ventas
    </a>
    <a href="inventario.php" class="nav-link text-white py-2 px-3 rounded <?= $pagina_actual === 'inventario.php' ? 'bg-primary' : 'hover-bg' ?>">
      <i class="bi bi-box me-2"></i> Inventario
    </a>
    <a href="compras.php" class="nav-link text-white py-2 px-3 rounded <?= $pagina_actual === 'compras.php' ? 'bg-primary' : 'hover-bg' ?>">
      <i class="bi bi-cart me-2"></i> Compras
    </a>
    <a href="trabajadores.php" class="nav-link text-white py-2 px-3 rounded <?= $pagina_actual === 'trabajadores.php' ? 'bg-primary' : 'hover-bg' ?>">
      <i class="bi bi-person-badge me-2"></i> Trabajadores
    </a>
    <a href="configuracion.php" class="nav-link text-white py-2 px-3 rounded <?= $pagina_actual === 'configuracion.php' ? 'bg-primary' : 'hover-bg' ?>">
      <i class="bi bi-gear me-2"></i> Configuración
    </a>
  </nav>
</div>

<style>
  .hover-bg:hover {
    background-color: rgba(255, 255, 255, 0.1);
    text-decoration: none;
  }
</style>
