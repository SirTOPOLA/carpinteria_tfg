
<?php
$rol = strtolower(trim($_SESSION['usuario']['rol'] ?? 'sin_permiso'));
$current = $_GET['vista'] ?? 'dashboard';

// Definir íconos por vista
$iconos = [
    'dashboard'    => 'bi-speedometer2',
    'usuarios'     => 'bi-people',
    'roles'        => 'bi-person-gear',
    'reportes'     => 'bi-graph-up',
    'productos'    => 'bi-box-seam',
    'ventas'       => 'bi-cash-stack',
    'compras'      => 'bi-cart-plus',
    'operaciones'  => 'bi-tools',
    'produccion'   => 'bi-gear-wide',
    'tareas'       => 'bi-list-check',
    'clientes'     => 'bi-person-badge',
    'proyectos'    => 'bi-kanban',
    'diseños'      => 'bi-brush',
    'perfil'       => 'bi-person-circle',
    'mis_pedidos'  => 'bi-bag-check'
];

// Submenús (solo para los módulos que los necesitan)
$submenus = [
    'usuarios' => [
        ['vista' => 'usuarios',            'label' => 'Listar',  'icono' => 'bi-list'],
        ['vista' => 'registrar_usuarios',  'label' => 'Crear',   'icono' => 'bi-plus'],
        ['vista' => 'editar_usuarios',     'label' => 'Editar',  'icono' => 'bi-pencil'],
    ],
    'productos' => [
        ['vista' => 'productos',           'label' => 'Listar',  'icono' => 'bi-list'],
        ['vista' => 'registrar_producto',  'label' => 'Crear',   'icono' => 'bi-plus'],
        ['vista' => 'editar_producto',     'label' => 'Editar',  'icono' => 'bi-pencil'],
    ],
    'roles' => [
        ['vista' => 'roles',               'label' => 'Listar',  'icono' => 'bi-list'],
        ['vista' => 'registrar_rol',       'label' => 'Crear',   'icono' => 'bi-plus'],
        ['vista' => 'editar_rol',          'label' => 'Editar',  'icono' => 'bi-pencil'],
    ]
];

// Menú por rol
$menu = [
    'administrador' => [
        'Dashboard'  => 'dashboard',
        'Usuarios'   => 'usuarios',
        'Roles'      => 'roles',
        'Reportes'   => 'reportes',
        'Productos'  => 'productos',
        'Ventas'     => 'ventas',
        'Compras'    => 'compras',
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
    ],
    'sin_permiso' => [
        'Dashboard' => 'dashboard',
    ],
];
?>

<div class="wrapper">
  <div id="sidebar" class="sidebar position-fixed scroll-box overflow-auto h-100 p-3">
    <h5 class="mb-4"><i class="bi bi-hammer me-2"></i>Menú</h5>
    <ul class="nav nav-pills flex-column">

    <?php foreach ($menu[$rol] as $label => $vistaName): 
      $icon = $iconos[$vistaName] ?? 'bi-chevron-right';
      $isActive = ($current === $vistaName || (isset($submenus[$vistaName]) && in_array($current, array_column($submenus[$vistaName], 'vista'))));
    ?>

      <?php if (isset($submenus[$vistaName])): ?>
        <li class="nav-item mb-1">
          <a href="#submenu-<?= $vistaName ?>" data-bs-toggle="collapse" class="nav-link <?= $isActive ? 'active' : '' ?>">
            <i class="bi <?= $icon ?> me-2"></i><?= $label ?>
          </a>
          <ul class="collapse list-unstyled ps-4 <?= $isActive ? 'show' : '' ?>" id="submenu-<?= $vistaName ?>">
            <?php foreach ($submenus[$vistaName] as $submenu): 
              $subActive = ($current === $submenu['vista']) ? 'active' : '';
            ?>
              <li>
                <a href="index.php?vista=<?= $submenu['vista'] ?>" class="nav-link <?= $subActive ?>">
                  <i class="bi <?= $submenu['icono'] ?> me-2"></i><?= $submenu['label'] ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </li>
      <?php else: ?>
        <li class="nav-item mb-1">
          <a href="index.php?vista=<?= $vistaName ?>" class="nav-link <?= $isActive ? 'active' : '' ?>">
            <i class="bi <?= $icon ?> me-2"></i><?= $label ?>
          </a>
        </li>
      <?php endif; ?>
    <?php endforeach; ?>

      <li class="nav-item mt-3">
        <a href="logout.php" class="nav-link text-danger">
          <i class="bi bi-box-arrow-right me-2"></i>Salir
        </a>
      </li>
    </ul>
  </div>
</div>
 

