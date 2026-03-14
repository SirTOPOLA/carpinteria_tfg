<?php

class RoleMiddleware
{
    public static function check($rolesPermitidos)
    {

        if (!isset($_SESSION['rol'])) {
            header("location:?page=login");
            exit;
        }

        if (!in_array($_SESSION['rol'], $rolesPermitidos)) {

            echo "No tienes permisos";
            exit;
        }
    }
}