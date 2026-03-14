<?php

session_start();
require "models/loginModel.php";

class LoginController
{

    public static function index()
    {
        if (isset($_SESSION['usuario'])) {
            header("location:?page=dashboard");
            exit;
        }

        require "views/frontend/login.php";
    }

    public static function login()
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("location:?page=login");
            exit;
        }

        $modelo = new Login();

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$username || !$password) {

            $_SESSION['msg'] = "Debe completar todos los campos";
            header("location:?page=login");
            exit;

        }

        $usuario = $modelo->login($username, $password);

        if ($usuario) {

            /* seguridad */

            session_regenerate_id(true);

            $_SESSION['usuario'] = $usuario['username'];
            $_SESSION['rol'] = $usuario['id_rol'];
            $_SESSION['id_usuario'] = $usuario['id_usuario'];

            header("location:?page=dashboard");
            exit;

        } else {

            $_SESSION['msg'] = "Credenciales incorrectas";
            header("location:?page=login");
            exit;

        }
    }

    public static function logout()
    {

        if (!isset($_SESSION['usuario'])) {
            header("location:?page=login");
            exit;
        }

        session_unset();
        session_destroy();

        header("location:?page=login");
        exit;
    }
}