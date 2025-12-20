<?php
require 'config/config.php';
$page = '';
if (isset($_GET['page']))
    $page = $_GET['page'];

switch ($page) {
    case 'home':
        require "controllers/homeController.php";
        HomeController::home();
        break;
    case '':
        require "controllers/homeController.php";
        HomeController::home();
        break;
    case 'login':
        require "controllers/loginController.php";
        LoginController::index();
        break;
    case 'loginAuth':
        require "controllers/loginController.php";
        LoginController::login();
        break;
    case 'logout':
        require "controllers/loginController.php";
        LoginController::logout();
        break;
    case 'admin':
        require "views/admin/dashboard.php";
        break;

    default:
        echo "<a href='" . urlsite . "?page=login'> LOGIN </a>";
        break;
}



