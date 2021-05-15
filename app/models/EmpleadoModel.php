<?php
require_once '../beans/Empleado.php';
require_once '../interfaces/ICRUD.php';
require_once '../db/AccesoDatos.php';

class EmpleadoModel implements ICRUD {

    private static PDO $dbAccess = AccesoDatos::obtenerInstancia();
    
    private static string $tabla = ''

    /*private static string $obtenerPorId = 'SELECT ()'

    public static function readById(mixed $id) : mixed {


    }*/

}