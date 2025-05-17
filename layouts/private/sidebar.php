









<?php
$rol = strtolower(trim($_SESSION['usuario']['rol'] ?? 'sin_permiso'));
$current = $_GET['vista'] ?? 'dashboard';

// Definir íconos por vista
// Definir íconos por vista
$iconos = [
  'dashboard'     => 'bi-speedometer2',
  'usuarios'      => 'bi-people',
  'empleados'     => 'bi-person-workspace',
  'roles'         => 'bi-person-gear',
  'reportes'      => 'bi-graph-up',
  'productos'     => 'bi-box-seam',
  'ventas'        => 'bi-cash-stack',
  'compras'       => 'bi-cart-plus',
  'operaciones'   => 'bi-tools',
  'produccion'    => 'bi-gear-wide',
  'producciones'  => 'bi-hammer',           // ícono relacionado a fabricación
  'tareas'        => 'bi-list-check',
  'clientes'      => 'bi-person-badge',
  'proyectos'     => 'bi-kanban',
  'diseños'       => 'bi-brush',
  'perfil'        => 'bi-person-circle',
  'mis_pedidos'   => 'bi-bag-check',
  'materiales'    => 'bi-layers',           // ícono sugerido para recursos
  'proveedores'   => 'bi-truck',            // ícono sugerido para proveedores
  'servicios'     => 'bi-plug',             // ícono relacionado a servicios
  'movimientos'   => 'bi-arrow-left-right', // entradas/salidas
  'pedidos'       => 'bi-bag',              // ícono relacionado a órdenes
];

// Menú por rol (usando vistas como clave para extraer íconos automáticamente)
$menu = [
    'administrador' => [
        'Dashboard'  => 'dashboard',
        'Empleados'  => 'empleados',
        'Usuarios'   => 'usuarios',
        'clientes'  => 'clientes',
        'materiales'  => 'materiales',
        'proveedores'  => 'proveedores',
        'servicios'  => 'servicios',
        'movimientos'  => 'movimientos',
        'pedidos'  => 'pedidos',
        'producciones'   => 'producciones',
        'proyectos'   => 'proyectos',
        'Roles'      => 'roles',
        'Reportes'   => 'reportes',
        'Productos'  => 'productos',
        'Ventas'     => 'ventas',
        'Compras'    => 'compras',
        'configuracion'    => 'configuracion',
        'Operaciones'=> 'operaciones',
    ],
    'operario' => [
        'Dashboard'   => 'dashboard',
        'Producción'  => 'produccion',
        'Tareas'      => 'tareas',
    ],
    'vendedor' => [
        'Dashboard' => 'dashboard',
        'Ventas'    => 'ventas',
        'Clientes'  => 'clientes',
    ],
    'diseñador' => [
        'Dashboard' => 'dashboard',
        'Proyectos' => 'proyectos',
        'Diseños'   => 'diseños',
    ],
    'cliente' => [
        'Dashboard'   => 'dashboard',
        'Mi Perfil'   => 'perfil',
        'Mis Pedidos' => 'mis_pedidos',
        'Pedidos' => 'pedidos',
    ],
    'sin_permiso' => [
        'inicio' => 'inicio',
    ],
];
?>
  <div class="wrapper">
  <div id="sidebar" class="sidebar position-fixed scroll-box overflow-auto h-100 p-3">
  <h5 class="mb-4"><i class="bi bi-hammer me-2"></i>Menú</h5>
  <ul class="nav nav-pills flex-column">
    <?php foreach ($menu[$rol] as $label => $vistaName): 
      $active = ($current === $vistaName) ? 'active' : '';
      $icon = $iconos[$vistaName] ?? 'bi-chevron-right'; // Fallback
    ?>
      <li class="nav-item mb-1">
        <a href="index.php?vista=<?= $vistaName ?>" class="nav-link <?= $active ?>">
          <i class="bi <?= $icon ?> me-2"></i><?= $label ?>
        </a>
      </li>
    <?php endforeach; ?>
    <li class="nav-item mt-3">
      <a href="logout.php" class="nav-link text-danger">
        <i class="bi bi-box-arrow-right me-2"></i> Salir
      </a>
    </li>
  </ul>
</div>
 
 