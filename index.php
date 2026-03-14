<?php

require "config/config.php";
require "core/Router.php";

session_start();

$router = new Router();

/* AUTH */
$router->get('login', 'LoginController@index');
$router->post('loginAuth', 'LoginController@login');
$router->get('logout', 'LoginController@logout');

/* DASHBOARD */
$router->get('dashboard', 'HomeController@dashboard');

/* USUARIOS */
$router->get('usuarios', 'UsuarioController@index');
$router->get('usuarioCrear', 'UsuarioController@crear');
$router->post('usuarioGuardar', 'UsuarioController@guardar');
$router->get('usuarioEditar', 'UsuarioController@editar');
$router->post('usuarioActualizar', 'UsuarioController@actualizar');
$router->get('usuarioEliminar', 'UsuarioController@eliminar');
$router->get('usuarioEstadoAjax', 'UsuarioController@estadoAjax');


/* ROLES */
$router->get('roles', 'RolController@index');           // Ver lista
$router->post('rolGuardar', 'RolController@guardar');     // Crear nuevo
$router->post('rolActualizar', 'RolController@actualizar'); // Editar existente
$router->get('rolEliminar', 'RolController@eliminar');   // Borrar

/* EMPLEADOS */
$router->get('empleados', 'EmpleadoController@index');
$router->post('empleadosGuardar', 'EmpleadoController@guardar');     // Crear nuevo
$router->post('empleadosActualizar', 'EmpleadoController@actualizar'); // Editar existente
$router->get('empleadosEliminar', 'EmpleadoController@eliminar');   // Borrar
$router->get('empleadoPerfil', 'EmpleadoController@detalle');  
$router->get('empleadoEstado', 'EmpleadoController@estadoAjax');  

/* DEFAULT */
$router->get('home', 'HomeController@home');

$router->dispatch();