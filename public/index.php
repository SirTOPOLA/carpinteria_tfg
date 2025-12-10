<?php
session_start();

require_once "../system/core/Router.php";
require_once "../app/config/app.php";
require_once "../app/config/database.php";

$router = new Router();

// Cargar rutas
require_once "../app/routes/web.php";

// Ejecutar
$router->dispatch();
