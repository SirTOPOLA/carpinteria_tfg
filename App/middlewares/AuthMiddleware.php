<?php

class AuthMiddleware {
    public static function check() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: /?view=login");
            exit;
        }
    }
}
