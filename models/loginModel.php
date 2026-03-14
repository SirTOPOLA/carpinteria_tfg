<?php

require "conexion.php";

class Login
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function login($username, $password)
    {
        try {

            $this->conexion->conectar();

            $sql = "SELECT id_usuario, username, password_hash, id_rol, activo
                    FROM usuarios
                    WHERE username = :username
                    LIMIT 1";

            $stmt = $this->conexion->pdo->prepare($sql);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return false;
            }

            if (!$user['activo']) {
                return false;
            }

            if (!password_verify($password, $user['password_hash'])) {
                return false;
            }

            /* actualizar ultimo login */

            $update = $this->conexion->pdo->prepare(
                "UPDATE usuarios 
                 SET ultimo_login = NOW() 
                 WHERE id_usuario = :id"
            );

            $update->bindParam(":id", $user['id_usuario'], PDO::PARAM_INT);
            $update->execute();

            $this->conexion->desconectar();

            return $user;

        } catch (Exception $e) {

            return false;

        }
    }
}