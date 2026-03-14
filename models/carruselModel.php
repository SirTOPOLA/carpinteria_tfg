<?php

require_once "conexion.php";

class Carrusel{

    public static function obtenerImagenes(){

        $db = new Conexion();
        $db->conectar();

        $sql = "SELECT 
                    ruta,
                    descripcion
                FROM archivos
                WHERE tipo = 'proyecto'
                ORDER BY fecha_subida DESC
                LIMIT 5";

        $stmt = $db->pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll();

        $db->desconectar();

        return $data;
    }

}