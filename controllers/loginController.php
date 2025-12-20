<?php
session_start();
require "models/loginModel.php";

class LoginController
{
    public static function index()
    {
      /*   if (isset($_SESSION['usuario'])) 
            header('location:' . urlsite); */
        
        require  "views/frontend/login.php";
    }
    public static function login()
    {
        $_modelo = new Login();
        $_username = trim($_POST['username']);
        $_pass = trim($_POST['password_hash']);

        /* Validamos desde el modelo */
        $_resultado = $_modelo->login($_username, $_pass);
        if ($_resultado) {
            $_SESSION['usuario'] = $_username;
            header('location:' . urlsite."?page=admin");
        } else {
            $_SESSION['msg'] = "No coinciden las credenciales";
            header('location:' . urlsite . "?page=login");
        }
    }
    public static function logout(){
        if(!isset($_SESSION['usuario'])){
            header("location: ".urlsite);
        }
        unset($_SESSION['usuario']);
        session_destroy();
        header('location: '.urlsite);
    }
}