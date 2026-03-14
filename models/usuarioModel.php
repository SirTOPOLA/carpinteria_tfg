<?php
require_once "conexion.php";

class UsuarioModel
{
    public static function listar()
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sql = "SELECT u.*, r.nombre AS rol, e.nombre AS empleado
                FROM usuarios u
                LEFT JOIN roles r ON r.id_rol = u.id_rol
                LEFT JOIN empleados e ON e.id_empleado = u.id_empleado
                ORDER BY u.id_usuario DESC";

        $stmt = $conexion->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function obtener($id)
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sql = "SELECT * FROM usuarios WHERE id_usuario=?";

        $stmt = $conexion->pdo->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public static function crear($datos)
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sql = "INSERT INTO usuarios
        (id_empleado,username,password_hash,id_rol)
        VALUES (?,?,?,?)";

        $stmt = $conexion->pdo->prepare($sql);

        return $stmt->execute([
            $datos["empleado"],
            $datos["username"],
            password_hash($datos["password"], PASSWORD_DEFAULT),
            $datos["rol"]
        ]);
    }

    public static function actualizar($datos)
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sql = "UPDATE usuarios
                SET username=?, id_rol=?, activo=?
                WHERE id_usuario=?";

        $stmt = $conexion->pdo->prepare($sql);

        return $stmt->execute([
            $datos["username"],
            $datos["rol"],
            $datos["activo"],
            $datos["id"]
        ]);
    }

    /**
     * Alterna el acceso al sistema (Prueba Social de Control)
     */
    public static function actualizarEstado($id)
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sqlActual = "SELECT activo FROM usuarios WHERE id_usuario = ?";
        $stmtActual = $conexion->pdo->prepare($sqlActual);
        $stmtActual->execute([$id]);
        $user = $stmtActual->fetch();

        if ($user) {
            $nuevoEstado = $user['activo'] == 1 ? 0 : 1;
            $sql = "UPDATE usuarios SET activo = ? WHERE id_usuario = ?";
            $stmt = $conexion->pdo->prepare($sql);
            return $stmt->execute([$nuevoEstado, $id]);
        }
        return false;
    }

    public static function eliminar($id)
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sql = "DELETE FROM usuarios WHERE id_usuario=?";

        $stmt = $conexion->pdo->prepare($sql);

        return $stmt->execute([$id]);
    }
}