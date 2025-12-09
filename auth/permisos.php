<?php 
if (session_status() == PHP_SESSION_NONE) { 
    session_start();
}

/**
 * Verifica permisos de acceso según la vista
 * Usa el campo 'rol' de la sesión creado en el login
 */
function verificarAcceso($vista)
{
    // Si no hay sesión → redirige a login
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php?vista=inicio");
        exit;
    }

    // Validar usuario activo
    if (isset($_SESSION['usuario']['activo']) && (int)$_SESSION['usuario']['activo'] !== 1) {
        $_SESSION['alerta'] = "Tu cuenta está inactiva. Contacta al administrador.";
        header("Location: index.php?vista=inicio");
        exit;
    }

    // Rol del usuario (normalizado en minúsculas)
    $rol = strtolower(trim($_SESSION['usuario']['rol'] ?? ''));

    // Estructura de permisos según el modelo profesional de base de datos
    $permisos = [
        'administrador' => [
            'dashboard',

            // Producción y proyectos
            'producciones', 'registrar_producciones', 'editar_producciones',
            'proyectos', 'registrar_proyecto', 'editar_proyecto', 'detalles_proyecto',

            // Inventario y materiales
            'materiales', 'registrar_materiales', 'editar_materiales',

            // Clientes
            'clientes', 'registrar_clientes', 'editar_clientes',

            // Movimientos de caja / contabilidad
            'movimientos', 'registrar_movimientos', 'editar_movimientos',

            // Pedidos / ventas personalizadas
            'pedidos', 'registrar_pedidos', 'editar_pedidos', 'detalles_pedidos',

            // Usuarios / roles / empleados
            'usuarios', 'registrar_usuarios', 'editar_usuarios',
            'empleados', 'registrar_empleado', 'editar_empleado',
            'roles', 'registrar_roles', 'editar_roles',

            // Proveedores y compras
            'proveedores', 'registrar_proveedores', 'editar_proveedores',
            'compras', 'registrar_compras', 'editar_compras', 'detalles_compras',

            // Productos (catálogo) y servicios externos
            'productos', 'registrar_productos', 'editar_productos',
            'servicios', 'registrar_servicios', 'editar_servicios',

            // Ventas generales
            'ventas', 'registrar_ventas', 'editar_ventas',

            // Configuración
            'configuracion', 'entidad',

            // Operaciones internas
            'operaciones',

            // Sección del cliente
            'mis_pedidos',

            // Reportes
            'reportes'
        ],

        'operario' => [
            'dashboard',
            'operaciones',
            'proyectos', 'detalles_proyecto',
        ],

        'cliente' => [
            'dashboard',
            'mis_pedidos'
        ]
    ];


    // Validación básica de vista (evita inyecciones o rutas peligrosas)
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $vista)) {
        $_SESSION['alerta'] = "La vista solicitada no es válida.";
        header("Location: index.php?vista=dashboard");
        exit;
    }

    // ¿El rol tiene permiso para esta vista?
    if (!in_array($vista, $permisos[$rol] ?? [])) {
        $_SESSION['alerta'] = "No tienes permiso para acceder a esta vista.";
        header("Location: index.php?vista=dashboard");
        exit;
    }
}



/**
 * Valida el acceso de un usuario autenticado a un módulo concreto
 *
 * @param array $rolesPermitidos → ejemplos: ['administrador', 'operario']
 * @param PDO $pdo
 */
function ValidarAcceso(array $rolesPermitidos, PDO $pdo)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Validar sesión activa
    if (empty($_SESSION['usuario'])) {
        $_SESSION['alerta'] = "Debes iniciar sesión para continuar.";
        header('Location: index.php?vista=inicio');
        exit;
    }

    // Validar actividad del usuario (campo activo de usuarios)
    if (isset($_SESSION['usuario']['activo']) && (int)$_SESSION['usuario']['activo'] !== 1) {
        $_SESSION['alerta'] = "Tu cuenta está inactiva. Contacta al administrador.";
        header('Location: index.php?vista=inicio');
        exit;
    }

    // Validar rol contra la lista permitida
    $rol = strtolower(trim($_SESSION['usuario']['rol']));
    $rolesPermitidos = array_map('strtolower', $rolesPermitidos);

    if (!in_array($rol, $rolesPermitidos)) {
        $_SESSION['alerta'] = "No tienes permisos para acceder a esta sección.";
        header('Location: index.php?vista=dashboard');
        exit;
    }

    // Validar conexión a la base de datos
    if (!$pdo instanceof PDO) {
        $_SESSION['alerta'] = "Error interno de conexión. Intenta nuevamente.";
        header('Location: index.php?vista=dashboard');
        exit;
    }
}
