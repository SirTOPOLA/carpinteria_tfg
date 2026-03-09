<?php

require_once "conexion.php";

class Proyecto{

    public static function obtenerProyectos(){

        $db = new Conexion();
        $db->conectar();

        $sql = "SELECT 
                    p.id_proyecto,
                    p.nombre_proyecto,
                    p.descripcion,
                    a.ruta AS imagen
                FROM proyectos p
                LEFT JOIN proyectos_imagenes pi 
                    ON p.id_proyecto = pi.id_proyecto
                LEFT JOIN archivos a 
                    ON pi.id_archivo = a.id_archivo
                ORDER BY p.fecha_registro DESC
                LIMIT 6";

        $stmt = $db->pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll();

        $db->desconectar();

        return $data;
    }

}