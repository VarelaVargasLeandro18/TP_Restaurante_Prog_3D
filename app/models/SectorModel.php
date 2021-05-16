<?php

require_once __DIR__ . '/../beans/Sector.php';
require_once __DIR__ . '/../db/AccesoDatos.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';

class SectorModel implements ICRUD {

    private static $columnaId = 'Id';
    private static $columnaNombre = 'Nombre';

    private static function crearSector(array $assoc) : Sector {
        $Id = intval($assoc[self::$columnaId]);
        $Nombre = intval($assoc[self::$columnaNombre]);
        
        return new Sector ( $Id, $Nombre );
    }

    private static function crearSectores(array $allAssoc) : array {
        $ret = array();
        
        foreach ( $allAssoc as $key => $assoc ) {
            $ret [$key] = self::crearSector($assoc);
        }

        return $ret;
    }

    public static function readById(mixed $id): mixed
    {
        $ret = NULL;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'SELECT 	Sector.Id, Sector.Nombre
            FROM Sector
            WHERE Sector.Id = :id;'
        );

        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $executed = $statement->execute();

        if ( $executed ) {
            $assoc = $statement->fetch(PDO::FETCH_ASSOC);
            $ret = ($assoc !== false) ? self::crearSector($assoc) : $ret;
        }

        return $ret;
    }

    public static function readAllObjects(): array
    {
        $ret = array();
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'SELECT Sector.Id, Sector.Nombre
            FROM Sector;'
        );
        $executed = $statement->execute();

        if ( $executed ) {
            $allAssoc = $statement->fetchAll(PDO::FETCH_ASSOC);
            $ret = ($allAssoc !== false) ? self::crearSectores($allAssoc) : $ret;
        }

        return $ret;
    }

    public static function insertObject(mixed $obj): bool
    {
        $ret = false;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'INSERT INTO Sector (Sector.Nombre)
            VALUES (:nombre);'
        );
        $statement->bindValue(':nombre', $obj->getNombre());
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
                FROM Sector
                WHERE Sector.Id = :id;'
            );

            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $executed = $statement->execute();
        }

        return $ret;
    }

    public static function updateObject(mixed $obj): bool
    {
        $ret = false;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'UPDATE Sector
            SET Sector.Nombre = :nombre
            WHERE Sector.Id = :id;'
        );
        $statement->bindValue(':id', $obj->getId(), PDO::PARAM_INT);
        $statement->bindValue(':nombre', $obj->getNombre());
        $ret = $statement->execute();

        return $ret;
    }

}