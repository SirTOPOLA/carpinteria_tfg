 

<div class="sidebar bg-dark d-none d-md-block p-1 vh-100 overflow-auto  mt-5" style="position: fixed" id="sidebar">
  <h5 class="text-white">Menú</h5>
   
  <!-- Botón para abrir el menú en pantallas pequeñas -->
  <nav class="navbar navbar-dark bg-dark d-lg-none px-3 py-2">
    <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
      <i class="bi bi-list"></i> Menú
    </button>
  </nav>
  <!-- Sidebar colapsable para móviles y fijo en pantallas grandes -->
  <div class="offcanvas-lg offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarMenu">
   
    <div class="offcanvas-body p-1">
      <ul class="nav nav-pills flex-column mb-auto">
  
        <li class="nav-item">
          <a href="../dashboard/index.php" class="nav-link text-white">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
          </a>
        </li>
  
        <li >
          <a href="#usuariosSubmenu" data-bs-toggle="collapse" class="nav-link text-white">
            <i class="bi bi-people me-2"></i> Usuarios 
          </a>
          <ul class="collapse list-unstyled ps-3" id="usuariosSubmenu">
            <li><a href="usuarios.php" class="nav-link text-white">Usuarios</a></li>
            <li><a href="empleados.php" class="nav-link text-white">empleados</a></li>
            <li><a href="roles.php" class="nav-link text-white">Roles</a></li>
           <!--  <li><a href="departamentos.php" class="nav-link text-white">departamentos</a></li> -->
          </ul>
        </li>
  
        <li>
          <a href="#clientesProveedoresSubmenu" data-bs-toggle="collapse" class="nav-link text-white">
            <i class="bi bi-person-lines-fill me-2"></i> Clientes / Proveedores
          </a>
          <ul class="collapse list-unstyled ps-3" id="clientesProveedoresSubmenu">
            <li><a href="clientes.php" class="nav-link text-white">Clientes</a></li>
            <li><a href="proveedores.php" class="nav-link text-white">Proveedores</a></li>
          </ul>
        </li>
  
        <li>
          <a href="#materialesSubmenu" data-bs-toggle="collapse" class="nav-link text-white">
            <i class="bi bi-box-seam me-2"></i> Materiales e Inventario
          </a>
          <ul class="collapse list-unstyled ps-3" id="materialesSubmenu">
            <li><a href="materiales.php" class="nav-link text-white">Materiales</a></li>
             
            <li><a href="movimientos_material.php" class="nav-link text-white">Movimientos</a></li>
          </ul>
        </li>
  
        <li>
          <a href="#productosSubmenu" data-bs-toggle="collapse" class="nav-link text-white">
            <i class="bi bi-hammer me-2"></i> Productos / Servicios
          </a>
          <ul class="collapse list-unstyled ps-3" id="productosSubmenu">
            <li><a href="producciones.php" class="nav-link text-white">Producciones</a></li>
            <li><a href="productos.php" class="nav-link text-white">Productos</a></li>
            <li><a href="categoria_producto.php" class="nav-link text-white">Categorías de Producto</a></li>
            <li><a href="servicios.php" class="nav-link text-white">Servicios</a></li>
            <li><a href="proyectos.php" class="nav-link text-white">Proyectos</a></li>
          </ul>
        </li>
  
        <li>
          <a href="#cotizacionesSubmenu" data-bs-toggle="collapse" class="nav-link text-white">
            <i class="bi bi-file-earmark-text me-2"></i> Cotizaciones
          </a>
          <ul class="collapse list-unstyled ps-3" id="cotizacionesSubmenu">
            <li><a href="cotizaciones.php" class="nav-link text-white">Listado</a></li>
            <li><a href="crear_cotizacion.php" class="nav-link text-white">Nueva Cotización</a></li>
          </ul>
        </li>
  
        <li>
          <a href="#ordenesSubmenu" data-bs-toggle="collapse" class="nav-link text-white">
            <i class="bi bi-tools me-2"></i> Órdenes de Trabajo
          </a>
          <ul class="collapse list-unstyled ps-3" id="ordenesSubmenu">
            <li><a href="ordenes_trabajo.php" class="nav-link text-white">Órdenes</a></li>
            <li><a href="trabajadores.php" class="nav-link text-white">Trabajadores</a></li>
          </ul>
        </li>
  
        <li>
          <a href="ventas.php" class="nav-link text-white">
            <i class="bi bi-cash-coin me-2"></i> Ventas
          </a>
        </li>
  
        <li>
          <a href="compras.php" class="nav-link text-white">
            <i class="bi bi-cart-check me-2"></i> Compras
          </a>
        </li>
  
        <li>
          <a href="#configSubmenu" data-bs-toggle="collapse" class="nav-link text-white">
            <i class="bi bi-gear me-2"></i> Configuración
          </a>
          <ul class="collapse list-unstyled ps-3" id="configSubmenu">
            <li><a href="configuracion.php" class="nav-link text-white">Datos del Sistema</a></li>
            <li><a href="logs.php" class="nav-link text-white">Logs del Sistema</a></li>
          </ul>
        </li>
      </ul>
  
      
    </div>
  </div>
  
</div>



<!-- Sidebar para móviles -->
<!-- <div class="sidebar-overlay d-md-none" style="position: fixed" id="sidebarOverlay">
  <div class="sidebar bg-dark p-3 vh-100 overflow-auto d-none" id="sidebarMobile">
    <button class="btn btn-outline-light mb-3" id="closeSidebar">Cerrar ✖</button>
  
    <ul class="nav flex-column">
      <li class="nav-item"><a href="#" class="nav-link text-white">Dashboard</a></li>
      <li class="nav-item"><a href="#" class="nav-link text-white">Usuarios</a></li>
      <li class="nav-item"><a href="#" class="nav-link text-white">Clientes</a></li>
      <li class="nav-item"><a href="#" class="nav-link text-white">Proveedores</a></li>
      <li class="nav-item"><a href="#" class="nav-link text-white">Productos</a></li>
      <li class="nav-item"><a href="#" class="nav-link text-white">Materiales</a></li>
      <li class="nav-item"><a href="#" class="nav-link text-white">Servicios</a></li>
      <li class="nav-item"><a href="#" class="nav-link text-white">Proyectos</a></li>
      <li class="nav-item"><a href="#" class="nav-link text-white">Ventas</a></li>
    </ul>
  </div>
</div> -->