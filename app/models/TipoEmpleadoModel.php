<?php
//set_include_path(__DIR__);

use Slim\Exception\HttpNotImplementedException;

require_once __DIR__ . '/../beans/TipoEmpleado.php';
require_once __DIR__ . '/../db/AccesoDatos.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';

class TipoEmpleadoModel implements ICRUD
{

    private static string $columnaId = 'Id';
    private static string $columnaTipo = 'Tipo';
    

    private static function crearTipoEmpleado(array $assoc ) : TipoEmpleado {
        $Id = intval( $assoc[self::$columnaId] );
        $tipo = $assoc[self::$columnaTipo];
        $ret = new TipoEmpleado($Id, $tipo);
        return $ret;
    }

    private static function crearTiposEmpleados( array $allAssoc ) : array {
        $ret = array();
        
        foreach ( $allAssoc as $key => $assoc ) {
            $ret [$key] = self::crearTipoEmpleado($assoc);
        }

        return $ret;
    }

    public static function readById(mixed $id): mixed
    {
        $ret = NULL;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'SELECT
            TipoEmpleado.Id,
            TipoEmpleado.Tipo
            FROM TipoEmpleado 
            WHERE TipoEmpleado.Id = :id;'
        );

        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $executed = $statement->execute();
        
        if ( $executed ) {
            $assoc = $statement->fetch(PDO::FETCH_ASSOC);
            $ret = ($assoc !== false) ? self::crearTipoEmpleado($assoc) : $ret; 
        }

        return $ret;
    }

    public static function readAllObjects(): array
    {
        $ret = array();
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'SELECT
            TipoEmpleado.Id,
            TipoEmpleado.Tipo
            FROM TipoEmpleado');
        $executed = $statement->execute();

        if ( $executed ) {
            $allAssoc = $statement->fetchAll(PDO::FETCH_ASSOC);
            $ret = ($allAssoc !== false) ? self::crearTiposEmpleados($allAssoc) : $ret;
        }

        return $ret;
    }

    public static function insertObject(mixed $obj): bool
    {
        $ret = false;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'INSERT 
            INTO TipoEmpleado (TipoEmpleado.Tipo)
            VALUES (:tipo);'
        );

        //$statement->bindValue(':id', $obj->getId(), PDO::PARAM_INT);
        $statement->bindValue(':tipo', $obj->getTipo());
        $ret = $statement->execute();
        return $ret;
    }

    public static function deleteById(mixed $id): mixed
    {
        $ret = self::readById($id);

        if ( $ret !== NULL ) {
            $access = AccesoDatos::obtenerInstancia();
            $statement = $access->prepararConsulta(
                'DELETE
                FROM TipoEmpleado
                WHERE TipoEmpleado.Id = :id;'
            );
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
        }

        return $ret;
    }

    public static function updateObject(mixed $obj): bool
    {
        $ret = false;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'UPDATE TipoEmpleado
            SET TipoEmpleado.Tipo = :tipo
            WHERE TipoEmpleado.Id = :id;'
        );

        $statement->bindValue(':id', $obj->getId(), PDO::PARAM_INT);
        $statement->bindValue(':tipo', $obj->getTipo());
        $ret = $statement->execute();
        return $ret;
    }

}
