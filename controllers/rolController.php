<?php
require_once "models/rolModel.php";
require_once "models/usuarioModel.php"; // Importante para verificar integridad

class RolController {
    
    public static function index() {
        $roles = RolModel::listar();
        require "views/dashboard/roles.php";
    }

    public static function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Limpieza de datos (Evita inyección de scripts HTML)
            $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS));
            $descripcion = trim(filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS));

            if (empty($nombre)) {
                header("Location: ?page=roles&error=campo_vacio");
                exit();
            }

            $datos = ["nombre" => $nombre, "descripcion" => $descripcion];
            $resultado = RolModel::crear($datos);

            $status = $resultado ? "creado" : "db_error";
            header("Location: ?page=roles&success=$status");
            exit();
        }
    }

    public static function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = filter_input(INPUT_POST, 'id_rol', FILTER_VALIDATE_INT);
            $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS));
            $descripcion = trim(filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS));

            if (!$id || empty($nombre)) {
                header("Location: ?page=roles&error=datos_invalidos");
                exit();
            }

            $datos = ["id_rol" => $id, "nombre" => $nombre, "descripcion" => $descripcion];
            RolModel::actualizar($datos);

            header("Location: ?page=roles&success=actualizado");
            exit();
        }
    }

    public static function eliminar() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header("Location: ?page=roles&error=id_invalido");
            exit();
        }

        // VALIDACIÓN DE INTEGRIDAD: ¿Hay usuarios usando este rol?
        // Necesitas crear el método 'contarPorRol' en tu UsuarioModel
        $totalUsuarios = UsuarioModel::contarPorRol($id);

        if ($totalUsuarios > 0) {
            // Bloqueamos el borrado porque el rol no está huérfano
            header("Location: ?page=roles&error=rol_en_uso");
            exit();
        }

        $resultado = RolModel::eliminar($id);
        $status = $resultado ? "eliminado" : "db_error";
        header("Location: ?page=roles&success=$status");
        exit();
    }
}