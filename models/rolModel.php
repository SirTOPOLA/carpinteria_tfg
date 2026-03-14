<?php
require_once "conexion.php";

class RolModel {
    
    // Helper para no repetir código de conexión
    private static function getConn() {
        $instancia = new Conexion();
        $instancia->conectar();
        return $instancia->pdo;
    }

    public static function listar() {
        $sql = "SELECT * FROM roles ORDER BY id_rol DESC";
        $stmt = self::getConn()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function obtener($id) {
        $sql = "SELECT * FROM roles WHERE id_rol = ?";
        $stmt = self::getConn()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function crear($datos) {
        $sql = "INSERT INTO roles (nombre, descripcion) VALUES (?, ?)";
        $stmt = self::getConn()->prepare($sql);
        return $stmt->execute([$datos["nombre"], $datos["descripcion"]]);
    }

    public static function actualizar($datos) {
        $sql = "UPDATE roles SET nombre = ?, descripcion = ? WHERE id_rol = ?";
        $stmt = self::getConn()->prepare($sql);
        return $stmt->execute([$datos["nombre"], $datos["descripcion"], $datos["id_rol"]]);
    }

    public static function eliminar($id) {
        // En este punto ya sabemos por el controlador que el rol está libre de usuarios
        $sql = "DELETE FROM roles WHERE id_rol = ?";
        $stmt = self::getConn()->prepare($sql);
        return $stmt->execute([$id]);
    }
}