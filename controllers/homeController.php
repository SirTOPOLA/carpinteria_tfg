<?php

require_once "models/servicioModel.php";
require_once "models/proyectoModel.php";
require_once "models/carruselModal.php";

class HomeController{

    public static function home(){

        $servicios = Servicio::obtenerServicios();

        $proyectos = Proyecto::obtenerProyectos();

        $obras = Carrusel::obtenerImagenes();

        require "views/frontend/home.php";
    }

}