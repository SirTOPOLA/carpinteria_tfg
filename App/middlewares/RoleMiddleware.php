<?php

class RoleMiddleware {

    public static function allow($roles = []) {
        AuthMiddleware::check(); // primero verificar sesión

        $rolUsuario = $_SESSION['usuario']['id_rol'] ?? null;

        if (!in_array($rolUsuario, $roles)) {
            header("Location: /?view=dashboard&error=403");
            exit;
        }
    }
}
