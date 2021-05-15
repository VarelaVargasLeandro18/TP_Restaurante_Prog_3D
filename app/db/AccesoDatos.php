<?php
class AccesoDatos
{
    private static $objAccesoDatos;
    private $objetoPDO;

    private function __construct()
    {
        try {
            $host = $_ENV['MYSQL_HOST'];
            $db = $_ENV['MYSQL_DB'];
            $usr = $_ENV['MYSQL_USER'];
            $pass = $_ENV['MYSQL_PASS'];

            $this->objetoPDO = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8', $usr, $pass, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->objetoPDO->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
            die();
        }
    }

    public static function obtenerInstancia() : self
    {
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new AccesoDatos();
        }
        return self::$objAccesoDatos;
    }

    public function prepararConsulta($sql) : PDOStatement
    {
        return $this->objetoPDO->prepare($sql);
    }

    public function obtenerUltimoId() : mixed
    {
        return $this->objetoPDO->lastInsertId();
    }

    public function __clone()
    {
        trigger_error('ERROR: La clonación de este objeto no está permitida', E_USER_ERROR);
    }
}