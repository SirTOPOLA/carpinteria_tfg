<?php
require_once "models/usuarioModel.php";

class UsuarioController
{

    public static function index()
    {
        $usuarios = UsuarioModel::listar();
        require "views/usuarios/index.php";
    }

    public static function crear()
    {
        require "views/usuarios/crear.php";
    }

    public static function guardar()
    {

        if ($_POST) {

            $datos = [
                "empleado" => $_POST["empleado"],
                "username" => $_POST["username"],
                "password" => $_POST["password"],
                "rol" => $_POST["rol"]
            ];

            UsuarioModel::crear($datos);

            header("Location: ?page=usuarios");
        }
    }

    public static function editar()
    {

        $id = $_GET["id"];
        $usuario = UsuarioModel::obtener($id);

        require "views/usuarios/editar.php";
    }

    public static function actualizar()
    {

        if ($_POST) {

            $datos = [
                "id" => $_POST["id"],
                "username" => $_POST["username"],
                "rol" => $_POST["rol"],
                "activo" => $_POST["activo"]
            ];

            UsuarioModel::actualizar($datos);

            header("Location:?page=usuarios");
        }
    }

    public static function eliminar()
    {

        $id = $_GET["id"];

        UsuarioModel::eliminar($id);

        header("Location:?page=usuarios");
    }
}