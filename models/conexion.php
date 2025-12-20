<?php
class Conexion
{
    public $pdo;
    public function conectar()
    {
        try {
            $dsn = "mysql:host=".DB_HOST .";dbname=" . DB_NAME.";charset=".DB_CARACTER;
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            );
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS);
        } catch (PDOException $e) {

            echo $e->getMessage();
        }
    }
    public function desconectar()
    {
        $this->pdo = null;
    }
}


