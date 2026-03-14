<?php
require_once "conexion.php";

class RolModel
{
    /**
     * Lista todos los niveles de acceso (Kiyosaki: Definir quién gestiona el activo)
     */
    public static function listar()
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sql = "SELECT * FROM roles ORDER BY id_rol DESC";

        $stmt = $conexion->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Obtiene un rol específico por su ID
     */
    public static function obtener($id)
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sql = "SELECT * FROM roles WHERE id_rol = ?";

        $stmt = $conexion->pdo->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Crea un nuevo rol (Hill: Estructura organizacional definida)
     */
    public static function crear($datos)
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sql = "INSERT INTO roles (nombre, descripcion) VALUES (?, ?)";

        $stmt = $conexion->pdo->prepare($sql);

        return $stmt->execute([
            $datos["nombre"],
            $datos["descripcion"]
        ]);
    }

    /**
     * Actualiza las facultades de un rol (Pink: Ajuste de Maestría)
     */
    public static function actualizar($datos)
    {
        $conexion = new Conexion();
        $conexion->conectar();

        $sql = "UPDATE roles 
                SET nombre = ?, descripcion = ? 
                WHERE id_rol = ?";

        $stmt = $conexion->pdo->prepare($sql);

        return $stmt->execute([
            $datos["nombre"],
            $datos["descripcion"],
            $datos["id_rol"]
        ]);
    }

    /**
     * Elimina un rol del sistema
     * Nota: En producción, se recomienda verificar que no haya usuarios asignados.
     */
    public static function eliminar($id)
    {
        $conexion = new Conexion();
        $conexion->conectar();

        // Cialdini: El control de salida es vital para la seguridad
        $sql = "DELETE FROM roles WHERE id_rol = ?";

        $stmt = $conexion->pdo->prepare($sql);

        return $stmt->execute([$id]);
    }
}