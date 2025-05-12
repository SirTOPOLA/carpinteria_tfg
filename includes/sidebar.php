
    <!-- Sidebar -->


    <!-- Botón hamburguesa -->
<button id="sidebarToggle" class="btn btn-dark">
  <i class="bi bi-list"></i>
</button>

<div class="wrapper">
<div id="sidebar" class="sidebar position-fixed scroll-box overflow-auto h-100 p-3">
      <h5 class="mb-4"><i class="bi bi-hammer me-2"></i>Menú</h5>
      <ul class="nav nav-pills flex-column">
        <li class="nav-item bg-primary ">
          <a href="index.php" class="nav-link "><i class="bi bi-speedometer2 me-2"></i><span>Dashboard</span></a>
        </li>

        <li class="nav-item">
          <a href="#usuarios" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-people me-2"></i><span>Usuarios</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="usuarios">
            <li><a href="usuarios.php" class="nav-link"><i class="bi bi-list me-2"></i><span>listar</span></a></li>
            <li><a href="registrar_usuarios.php" class="nav-link"><i class="bi bi-plus me-2"></i><span>Crear</span></a></li>
            <li><a href="editar_usuarios.php" class="nav-link"><i class="bi bi-pencil me-2"></i><span>Editar</span></a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#materiales" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-box-seam me-2"></i><span>Materiales</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="materiales">
            <li><a href="materiales.php" class="nav-link"><i class="bi bi-list me-2"></i><span>listar</span></a></li>
            <li><a href="registrar_materiales.php" class="nav-link"><i class="bi bi-plus me-2"></i><span>Crear</span></a></li>
            <li><a href="editar_material.php" class="nav-link"><i class="bi bi-pencil me-2"></i><span>Editar</span></a></li>
            <li>
              <a href="#movimientos" data-bs-toggle="collapse" class="nav-link">
                <i class="bi bi-archive me-2"></i><span>Movimientos</span>
              </a>
              <ul class="collapse list-unstyled ps-4" id="movimientos">
                <li><a href="movimientos_material.php" class="nav-link"><i class="bi bi-list me-2"></i><span>listar</span></a></li>
                <li><a href="registrar_movimientos_material.php" class="nav-link"><i class="bi bi-plus me-2"></i><span>Crear</span></a></li>
                <li><a href="editar_movimientos_material.php" class="nav-link"><i class="bi bi-pencil me-2"></i><span>Editar</span></a></li>
              </ul>

              
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#proveedor" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-person-workspace me-2"></i><span>Proveedor</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="proveedor">
            <li><a href="proveedores.php" class="nav-link"><i class="bi bi-list me-2"></i><span>listar</span></a></li>
            <li><a href="registrar_proveedores.php" class="nav-link"><i class="bi bi-plus me-2"></i><span>Crear</span></a></li>
            <li><a href="editar_proveedor.php" class="nav-link"><i class="bi bi-pencil me-2"></i><span>Editar</span></a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#roles" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-shield-lock me-2"></i><span>Roles</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="roles">
            <li><a href="roles.php" class="nav-link"><i class="bi bi-list me-2"></i><span>listar</span></a></li>
             <li><a href="editar_rol.php" class="nav-link"><i class="bi bi-pencil me-2"></i><span>Editar</span></a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#empleados" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-person-badge me-2"></i><span>Empleados</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="empleados">
            <li><a href="empleados.php" class="nav-link"><i class="bi bi-list me-2"></i><span>listar</span></a></li>
            <li><a href="registrar_empleado.php" class="nav-link"><i class="bi bi-plus me-2"></i><span>Crear</span></a></li>
            <li><a href="editar_empleado.php" class="nav-link"><i class="bi bi-pencil me-2"></i><span>Editar</span></a></li>
          </ul>
        </li>
       
        <li class="nav-item">
          <a href="#clientes" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-person-check me-2"></i><span>Clientes</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="clientes">
            <li><a href="clientes.php" class="nav-link"><i class="bi bi-list me-2"></i><span>listar</span></a></li>
            <li><a href="registrar_cliente.php" class="nav-link"><i class="bi bi-plus me-2"></i><span>Crear</span></a></li>
            <li><a href="editar_cliente.php" class="nav-link"><i class="bi bi-pencil me-2"></i><span>Editar</span></a></li>
          </ul>
        </li>
       

        <li class="nav-item">
          <a href="#producciones" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-building-gear me-2"></i><span>Producciones</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="producciones">
            <li><a href="producciones.php" class="nav-link"><i class="bi bi-list me-2"></i><span>listar</span></a></li>
            <li><a href="registrar_producciones.php" class="nav-link"><i class="bi bi-plus me-2"></i><span>Crear</span></a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-pencil me-2"></i><span>Editar</span></a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#productos" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-box me-2"></i><span>Productos</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="productos">
            <li><a href="#" class="nav-link"><i class="bi bi-list me-2"></i><span>listar</span></a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-plus me-2"></i><span>Crear</span></a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-pencil me-2"></i><span>Editar</span></a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#servicio" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-briefcase me-2"></i><span>Servicios</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="servicio">
            <li><a href="#" class="nav-link"><i class="bi bi-list me-2"></i><span>listar</span></a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-plus me-2"></i><span>Crear</span></a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-pencil me-2"></i><span>Editar</span></a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#proyectos" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-kanban me-2"></i><span>Proyectos</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="proyectos">
            <li><a href="proyectos.php" class="nav-link"><i class="bi bi-list me-2"></i><span>listar</span></a></li>
            <li><a href="registrar_proyectos.php" class="nav-link"><i class="bi bi-plus me-2"></i><span>Crear</span></a></li>
            <li><a href="editar_proyecto.php" class="nav-link"><i class="bi bi-pencil me-2"></i><span>Editar</span></a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#ventas" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-cash-coin me-2"></i><span>Ventas</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="ventas">
            <li><a href="#" class="nav-link"><i class="bi bi-list me-2"></i><span>listar</span></a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-plus me-2"></i><span>Crear</span></a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-pencil me-2"></i><span>Editar</span></a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#compras" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-cart-check me-2"></i><span>Compras</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="compras">
            <li><a href="compras.php" class="nav-link"><i class="bi bi-list me-2"></i><span>listar</span></a></li>
            <li><a href="registrar_compra.php" class="nav-link"><i class="bi bi-plus me-2"></i><span>Crear</span></a></li>
            <li><a href="editar_compra.php" class="nav-link"><i class="bi bi-pencil me-2"></i><span>Editar</span></a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#ordenes" data-bs-toggle="collapse" class="nav-link">
            <i class="bi bi-journal-text me-2"></i><span>Plan de trabajo</span>
          </a>
          <ul class="collapse list-unstyled ps-4" id="ordenes">
            <li><a href="#" class="nav-link"><i class="bi bi-list me-2"></i><span>listar</span></a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-plus me-2"></i><span>Crear</span></a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-pencil me-2"></i><span>Editar</span></a></li>
          </ul>
        </li>
        <li class="nav-item">
             <li>
               <a href="#" class="nav-link"><i class="bi bi-archive me-2"></i><span>Inventario</span></a> 
               
            </li>
        </li>
       <!--  <li class="nav-item">
             <li>
              <a href="configuracion.php" class="nav-link"><i class="bi bi-sliders2-vertical me-2"></i> Configuracion</a>
            </li>
        </li> -->

         

      </ul>
    </div>
    
  <div id="content">

  
 


   
      
      
      
      
      
      
      
      
      
      
      <!-- ------------------------- -->
     
     <!--  <li class="nav-item">
        <a href="#materialesSubmenu" data-bs-toggle="collapse" class="nav-link">
          <i class="bi bi-box-seam me-2"></i><span>Materiales</span>
        </a>
        <ul class="collapse list-unstyled ps-4" id="materialesSubmenu">
          <li><a href="#" class="nav-link"><i class="bi bi-arrow-left-right me-2"></i><span>Movimientos</span></a></li>
        </ul>
      </li> -->
 
  <!-- <li>
    <a href="#configSubmenu" data-bs-toggle="collapse" class="nav-link">
      <i class="bi bi-gear me-2"></i> Configuración
    </a>
  </li> -->
  
  


