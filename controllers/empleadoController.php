<?php
require_once "models/empleadoModel.php";

class EmpleadoController
{
    /**
     * Muestra la nómina completa
     */
    public static function index()
    {
        $empleados = EmpleadoModel::listar();
        require "views/empleados/index.php";
    }

    /**
     * Procesa la persistencia con validación y sanitización
     */
    public static function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 1. SANITIZACIÓN: Limpieza de entradas para prevenir XSS
            $datos = [
                "nombre"             => htmlspecialchars(trim($_POST["nombre"])),
                "apellido"           => htmlspecialchars(trim($_POST["apellido"])),
                "documento"          => htmlspecialchars(trim($_POST["documento"])),
                "telefono"           => filter_var($_POST["telefono"], FILTER_SANITIZE_NUMBER_INT),
                "correo"             => filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL),
                "direccion"          => htmlspecialchars(trim($_POST["direccion"])),
                "rol_laboral"        => htmlspecialchars(trim($_POST["rol_laboral"])),
                "salario_base"       => filter_var($_POST["salario_base"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                "fecha_contratacion" => $_POST["fecha_contratacion"]
            ];

            // 2. VALIDACIÓN ROBUSTA (Cialdini: Autoridad a través de la precisión)
            if (empty($datos["nombre"]) || empty($datos["documento"])) {
                header("Location: ?page=empleadoCrear&error=campos_obligatorios");
                exit();
            }

            if (!filter_var($datos["correo"], FILTER_VALIDATE_EMAIL)) {
                header("Location: ?page=empleadoCrear&error=email_invalido");
                exit();
            }

            if (!is_numeric($datos["salario_base"]) || $datos["salario_base"] < 0) {
                header("Location: ?page=empleadoCrear&error=salario_invalido");
                exit();
            }

            // 3. PERSISTENCIA
            $resultado = EmpleadoModel::crear($datos);

            if ($resultado) {
                header("Location: ?page=empleados&success=creado");
            } else {
                header("Location: ?page=empleadoCrear&error=db_error");
            }
            exit();
        }
    }

    /**
     * Gestión del Switch de Estado (Bandura: Control Instantáneo)
     */
    public static function estado()
    {
        if (isset($_GET["id"])) {
            $id = filter_var($_GET["id"], FILTER_SANITIZE_NUMBER_INT);
            
            if (EmpleadoModel::actualizarEstado($id)) {
                header("Location: ?page=empleados&success=estado_actualizado");
            } else {
                header("Location: ?page=empleados&error=error_estado");
            }
            exit();
        }
    }

    /**
     * Actualización con validación de ID
     */
    public static function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $id_empleado = filter_var($_POST["id_empleado"], FILTER_SANITIZE_NUMBER_INT);

            $datos = [
                "id_empleado"        => $id_empleado,
                "nombre"             => htmlspecialchars(trim($_POST["nombre"])),
                "apellido"           => htmlspecialchars(trim($_POST["apellido"])),
                "documento"          => htmlspecialchars(trim($_POST["documento"])),
                "telefono"           => htmlspecialchars(trim($_POST["telefono"])),
                "correo"             => filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL),
                "direccion"          => htmlspecialchars(trim($_POST["direccion"])),
                "rol_laboral"        => htmlspecialchars(trim($_POST["rol_laboral"])),
                "salario_base"       => (float)$_POST["salario_base"],
                "fecha_contratacion" => $_POST["fecha_contratacion"]
            ];

            // Validación mínima de integridad
            if ($id_empleado > 0 && !empty($datos["nombre"])) {
                EmpleadoModel::actualizar($datos);
                header("Location: ?page=empleados&success=actualizado");
            } else {
                header("Location: ?page=empleadoEditar&id=$id_empleado&error=datos_invalidos");
            }
            exit();
        }
    }

    /**
     * Eliminación segura
     */
    public static function eliminar()
    {
        if (isset($_GET["id"])) {
            $id = filter_var($_GET["id"], FILTER_SANITIZE_NUMBER_INT);
            EmpleadoModel::eliminar($id);
        }
        header("Location: ?page=empleados&success=eliminado");
        exit();
    }
}