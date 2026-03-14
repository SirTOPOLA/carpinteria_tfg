<?php

require_once "models/servicioModel.php";
require_once "models/proyectoModel.php";
require_once "models/carruselModel.php";
require_once "models/dashboardModel.php";
require_once "middleware/AuthMiddleware.php";

class HomeController
{

    public static function home()
    {
        try {

            $servicios = Servicio::obtenerServicios();
            $proyectos = Proyecto::obtenerProyectos();
            $obras = Carrusel::obtenerImagenes();

            require "views/frontend/home.php";

        } catch (Exception $e) {

            require "views/errors/500.php";

        }
    }


    public static function dashboard()
    {

        AuthMiddleware::check();

        try {

            $stats = DashboardModel::stats();

            require "views/dashboard/dashboard.php";

        } catch (Exception $e) {

            require "views/errors/500.php";

        }
    }
}