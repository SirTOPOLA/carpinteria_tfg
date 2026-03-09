<?php

require_once "conexion.php";

class Servicio{

    public static function obtenerServicios(){

        $db = new Conexion();
        $db->conectar();

        $sql = "SELECT 
                    id_servicio,
                    nombre,
                    descripcion,
                    precio_base
                FROM servicios
                ORDER BY nombre ASC";

        $stmt = $db->pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll();

        $db->desconectar();

        return $data;
    }

}