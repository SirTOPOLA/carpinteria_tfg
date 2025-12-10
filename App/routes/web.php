<?php
// app/routes/web.php

/** Rutas principales del sistema **/

$router->get('', [DashboardController::class, 'index']);
$router->get('login', [AuthController::class, 'showLogin']);
$router->post('login', [AuthController::class, 'login']);
$router->get('logout', [AuthController::class, 'logout']);

// Roles
$router->get('roles', [RolController::class, 'index']);
$router->get('roles/crear', [RolController::class, 'crear']);
$router->post('roles/crear', [RolController::class, 'store']);
$router->get('roles/editar', [RolController::class, 'editar']);
$router->post('roles/editar', [RolController::class, 'update']);

// Usuarios
$router->get('usuarios', [UsuarioController::class, 'index']);
$router->get('usuarios/crear', [UsuarioController::class, 'crear']);
$router->post('usuarios/crear', [UsuarioController::class, 'store']);
$router->get('usuarios/editar', [UsuarioController::class, 'editar']);
$router->post('usuarios/editar', [UsuarioController::class, 'update']);
