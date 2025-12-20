<?php
require "conexion.php";
class Login
{
    private $_conexion;
    public function __construct()
    {
        $this->_conexion = new Conexion();
    }
    public function login($userName, $passwordHash)
    {
        $this->_conexion->conectar();
        $stmt = $this->_conexion->pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->execute([$userName]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->_conexion->desconectar();
        if ($user && password_verify($passwordHash, $user['password_hash']) && $user['activo'] === 1) {
            return true;
        } else {
            return false;
        }
    }
}