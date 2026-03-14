<?php
require_once "models/rolModel.php";

class RolController
{
    /**
     * Muestra la lista principal de roles (Jerarquía de Seguridad)
     */
    public static function index()
    {
        // Aplicando el principio de Maestría (Pink): Control total del flujo
        $roles = RolModel::listar();
        require "views/roles/index.php";
    }

    /**
     * Carga la interfaz para definir un nuevo nivel de autoridad
     */
    public static function crear()
    {
        require "views/roles/crear.php";
    }

    /**
     * Procesa y protege la persistencia del nuevo rol
     */
    public static function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Estructura de datos basada en tu tabla 'roles'
            $datos = [
                "nombre"      => $_POST["nombre"],
                "descripcion" => $_POST["descripcion"]
            ];

            RolModel::crear($datos);

            // Redirección estratégica (Cialdini: Consistencia en el flujo operativo)
            header("Location: ?page=roles");
            exit();
        }
    }

    /**
     * Obtiene los datos de un rol específico para su ajuste
     */
    public static function editar()
    {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $rol = RolModel::obtener($id);
            require "views/roles/editar.php";
        } else {
            header("Location: ?page=roles");
        }
    }

    /**
     * Actualiza la estructura de permisos del activo empresarial
     */
    public static function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                "id_rol"      => $_POST["id_rol"],
                "nombre"      => $_POST["nombre"],
                "descripcion" => $_POST["descripcion"]
            ];

            RolModel::actualizar($datos);

            header("Location: ?page=roles");
            exit();
        }
    }

    /**
     * Elimina un rol del sistema (Requiere validación de integridad referencial)
     */
    public static function eliminar()
    {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            
            // Sugerencia: RolModel debe validar que no existan usuarios 
            // vinculados antes de proceder (Hill: Mente Maestra / Prevención)
            RolModel::eliminar($id);
        }

        header("Location: ?page=roles");
        exit();
    }
}