<?php
require_once "models/usuarioModel.php";
require_once "models/empleadoModel.php";
require_once "models/rolModel.php";

class UsuarioController {
    
    public static function index() {
        $usuarios = UsuarioModel::listar();
        $empleados = EmpleadoModel::listar(); // Para cargar el select del modal
        $roles = RolModel::listar();          // Para cargar el select del modal
        require "views/dashboard/usuario.php";
    }

    public static function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitización robusta
            $username = htmlspecialchars(trim($_POST["username"]));
            $password = $_POST["password"];
            $id_empleado = filter_var($_POST["id_empleado"], FILTER_VALIDATE_INT);
            $id_rol = filter_var($_POST["id_rol"], FILTER_VALIDATE_INT);

            // Validación: Contraseña mínima de 6 caracteres (Pink: Seguridad intrínseca)
            if (!$username || strlen($password) < 6 || !$id_rol) {
                header("Location: ?page=usuarios&error=datos_invalidos");
                exit();
            }

            $datos = [
                "empleado" => $id_empleado ?: null,
                "username" => $username,
                "password" => $password,
                "rol"      => $id_rol
            ];

            UsuarioModel::crear($datos);
            header("Location: ?page=usuarios&success=creado");
        }
    }


    public static function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                "id"       => filter_var($_POST["id"], FILTER_VALIDATE_INT),
                "username" => htmlspecialchars(trim($_POST["username"])),
                "rol"      => filter_var($_POST["id_rol"], FILTER_VALIDATE_INT),
                "activo"   => isset($_POST["activo"]) ? 1 : 0
            ];

            UsuarioModel::actualizar($datos);
            header("Location: ?page=usuarios&success=actualizado");
        }
    }

    public static function estadoAjax() {
        // 1. Limpiar cualquier buffer de salida previo (evita espacios en blanco o warnings)
        if (ob_get_length()) ob_clean();
        
        // 2. Establecer el encabezado JSON
        header('Content-Type: application/json');
    
        try {
            if (!isset($_GET["id"])) {
                throw new Exception('ID no proporcionado');
            }
    
            $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);
            if (!$id) {
                throw new Exception('ID inválido');
            }
    
            $resultado = UsuarioModel::actualizarEstado($id);
            
            if ($resultado) {
                echo json_encode(['status' => 'success', 'message' => 'Estado actualizado']);
            } else {
                throw new Exception('No se pudo cambiar el estado en la base de datos');
            }
    
        } catch (Exception $e) {
            // Opcional: enviar un código de error HTTP
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    
        // 3. ¡CRUCIAL! Detener la ejecución para que no se carguen vistas o footers
        exit();
    }
}