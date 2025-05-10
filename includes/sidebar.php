<!-- Sidebar -->
<div class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebarMenu">
  <div class="offcanvas-body p-3">
    <h5 class="mb-3">Menú Principal</h5>
    <ul class="nav nav-pills flex-column mb-auto">

      <li><a href="index.php" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>

      <li>
        <a href="#usuariosSubmenu" data-bs-toggle="collapse" class="nav-link">
          <i class="bi bi-people me-2"></i> Usuarios
        </a>
        <ul class="collapse list-unstyled ps-3" id="usuariosSubmenu">
          <li><a href="usuarios.php" class="nav-link"><i class="bi bi-person me-2"></i> Usuarios</a></li>
          <li><a href="empleados.php" class="nav-link"><i class="bi bi-person-badge me-2"></i> Empleados</a></li>
          <li><a href="roles.php" class="nav-link"><i class="bi bi-shield-lock me-2"></i> Roles</a></li>
        </ul>
      </li>

      <li>
        <a href="#clientesProveedoresSubmenu" data-bs-toggle="collapse" class="nav-link">
          <i class="bi bi-person-lines-fill me-2"></i> Clientes / Proveedores
        </a>
        <ul class="collapse list-unstyled ps-3" id="clientesProveedoresSubmenu">
          <li><a href="clientes.php" class="nav-link"><i class="bi bi-person-check me-2"></i> Clientes</a></li>
          <li><a href="proveedores.php" class="nav-link"><i class="bi bi-person-workspace me-2"></i> Proveedores</a></li>
        </ul>
      </li>

      <li>
        <a href="#materialesSubmenu" data-bs-toggle="collapse" class="nav-link">
          <i class="bi bi-box-seam me-2"></i> Materiales
        </a>
        <ul class="collapse list-unstyled ps-3" id="materialesSubmenu">
          <li><a href="materiales.php" class="nav-link"><i class="bi bi-archive me-2"></i> Inventario</a></li>
          <li><a href="movimientos_material.php" class="nav-link"><i class="bi bi-arrow-left-right me-2"></i> Movimientos</a></li>
        </ul>
      </li>

      <li>
        <a href="#productosSubmenu" data-bs-toggle="collapse" class="nav-link">
          <i class="bi bi-hammer me-2"></i> Productos / Servicios
        </a>
        <ul class="collapse list-unstyled ps-3" id="productosSubmenu">
          <li><a href="producciones.php" class="nav-link"><i class="bi bi-building-gear me-2"></i> Producciones</a></li>
          <li><a href="productos.php" class="nav-link"><i class="bi bi-box me-2"></i> Productos</a></li>
          <li><a href="servicios.php" class="nav-link"><i class="bi bi-briefcase me-2"></i> Servicios</a></li>
          <li><a href="proyectos.php" class="nav-link"><i class="bi bi-kanban me-2"></i> Proyectos</a></li>
        </ul>
      </li>

      <li>
        <a href="#ordenesSubmenu" data-bs-toggle="collapse" class="nav-link">
          <i class="bi bi-tools me-2"></i> Órdenes de Trabajo
        </a>
        <ul class="collapse list-unstyled ps-3" id="ordenesSubmenu">
          <li><a href="ordenes_trabajo.php" class="nav-link"><i class="bi bi-journal-text me-2"></i> Órdenes</a></li>
          <li><a href="trabajadores.php" class="nav-link"><i class="bi bi-person-gear me-2"></i> Trabajadores</a></li>
        </ul>
      </li>

      <li><a href="ventas.php" class="nav-link"><i class="bi bi-cash-coin me-2"></i> Ventas</a></li>
      <li><a href="compras.php" class="nav-link"><i class="bi bi-cart-check me-2"></i> Compras</a></li>

      <li>
        <a href="#configSubmenu" data-bs-toggle="collapse" class="nav-link">
          <i class="bi bi-gear me-2"></i> Configuración
        </a>
        <ul class="collapse list-unstyled ps-3" id="configSubmenu">
          <li><a href="configuracion.php" class="nav-link"><i class="bi bi-sliders2-vertical me-2"></i> Sistema</a></li>
          <li><a href="logs.php" class="nav-link"><i class="bi bi-journal-code me-2"></i> Logs</a></li>
        </ul>
      </li>

    </ul>
  </div>
</div>