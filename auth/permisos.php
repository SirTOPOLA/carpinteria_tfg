<?php
// Verificar si la sesión ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
    // Si la sesión no está iniciada, se inicia
    session_start();
}


function verificarAcceso($vista)
{
    // Si no hay sesión → redirige a login
    if (!isset($_SESSION['usuario'])) {
        header("Location: login.php");
        exit;
    }

    // Normalizar el rol a minúsculas
    $rol = strtolower(trim($_SESSION['usuario']['rol'] ?? ''));

    // Estructura de permisos (todos en minúsculas)
    $permisos = [
        'administrador' => [
            'dashboard',
            'usuarios', 'registrar_usuarios', 'editar_usuarios',
            'empleados', 'registrar_empleado', 'editar_empleado',
            'roles', 'registrar_roles', 'editar_roles',
            'reportes',
            'productos', 'productos_listar', 'productos_crear',
            'ventas',
            'compras',
            'operaciones'
        ],
        'operario' => ['dashboard', 'produccion', 'tareas'],
        'vendedor' => ['dashboard', 'ventas', 'clientes'],
        'diseñador' => ['dashboard', 'proyectos', 'diseños'],
        'cliente' => ['dashboard', 'perfil', 'mis_pedidos'],
        '' => ['inicio']
    ];
    

    // Validación del nombre de la vista para prevenir rutas maliciosas
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $vista)) {
        $_SESSION['alerta'] = "La vista solicitada no es válida.";
        header("Location: index.php?vista=dashboard");
        exit;
    }

    // Si el rol no tiene permiso para esta vista → redirige a dashboard
    if (!in_array($vista, $permisos[$rol] ?? [])) {
        $_SESSION['alerta'] = "No tienes permiso para acceder a la vista solicitada.";
        header("Location: index.php?vista=dashboard");
        exit;
    }
}


