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


/* ROLES */
$router->get('roles', 'RolController@index');

/* EMPLEADOS */
$router->get('empleados', 'EmpleadoController@index');

/* DEFAULT */

$router->get('home', 'HomeController@home');

$router->dispatch();