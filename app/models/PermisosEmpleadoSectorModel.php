<?php

require_once __DIR__ . '/../beans/PermisosEmpleadoSector.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';
require_once __DIR__ . '/../db/AccesoDatos.php';
require_once __DIR__ . '/TipoEmpleadoModel.php';
require_once __DIR__ . '/SectorModel.php';

class PermisosEmpleadoSectorModel implements ICRUD {

    private static string $columnaId = 'Id';
    private static string $columnaTipoE = 'TipoEmpleadoId';
    private static string $columnaSector = 'SectorId';

    private static function crearPermisosEmpleadosSector ( array $allAssoc ) : array {
        $ret = array();
        
        foreach ( $allAssoc as $key => $assoc ) {
            $ret [$key] = self::crearPermisoEmpleadoSector($assoc);
        }

        return $ret;
    }
    
    private static function crearPermisoEmpleadoSector(array $assoc) : PermisosEmpleadoSector {
        $Id = intval($assoc[self::$columnaId]);
        $idTipoE = intval($assoc[self::$columnaTipoE]);
        $idSector = intval($assoc[self::$columnaSector]);

        $Sector = SectorModel::readById($idSector);
        $Tipo = TipoEmpleadoModel::readById($idTipoE);

        return new PermisosEmpleadoSector($Id, $Tipo, $Sector);
    }

    public static function readById(mixed $id): mixed
    {
        $ret = NULL;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'SELECT 	PermisosEmpleadoSector.Id, PermisosEmpleadoSector.TipoEmpleadoId, PermisosEmpleadoSector.SectorId
            FROM PermisosEmpleadoSector
            WHERE PermisosEmpleadoSector.Id = :id;'
        );
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $executed = $statement->execute();

        if ( $executed ) {
            $assoc = $statement->fetch(PDO::FETCH_ASSOC);
            $ret = ($assoc !== false) ? self::crearPermisoEmpleadoSector($assoc) : NULL;
        }

        return $ret;
    }

    public static function readAllObjects(): array
    {
        $ret = array();
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'SELECT 	PermisosEmpleadoSector.Id, PermisosEmpleadoSector.TipoEmpleadoId, PermisosEmpleadoSector.SectorId
            FROM PermisosEmpleadoSector;'
        );
        $executed = $statement->execute();
        if ( $executed ) {
            $allAssoc = $statement->fetchAll(PDO::FETCH_ASSOC);
            $ret = ($allAssoc !== false) ? self::crearPermisosEmpleadosSector($allAssoc) : NULL;
        }

        return $ret;
    }

    public static function insertObject(mixed $obj): bool
    {
        $ret = false;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            '
            INSERT INTO PermisosEmpleadoSector (PermisosEmpleadoSector.TipoEmpleadoId, PermisosEmpleadoSector.SectorId)
                    VALUES (:idTipoE, :idSector);'
        );
        $statement->bindValue(':idTipoE', $obj->getTipo()->getId());
        $statement->bindValue(':idSector', $obj->getSector()->getId());
        $ret = $statement->execute();

        return $ret;  
    }

    public static function deleteById(mixed $id): mixed
    {
        $ret = self::readById($id);
        
        if ( $ret !== NULL ) {
            $access = AccesoDatos::obtenerInstancia();
            $statement = $access->prepararConsulta(
                '
                DELETE 
                FROM PermisosEmpleadoSector
                wHERE PermisosEmpleadoSector.Id = :id;'
            );

            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
        }
        return $ret;
    }

    public static function updateObject(mixed $obj): bool
    {
        $ret = false;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            '
            UPDATE PermisosEmpleadoSector
            SET PermisosEmpleadoSector.TipoEmpleadoId = :idTipoE, PermisosEmpleadoSector.SectorId = :idSector
            WHERE PermisosEmpleadoSector.Id = :id;'
        );
        $statement->bindValue(':id', $obj->getId(), PDO::PARAM_INT);
        $statement->bindValue(':idTipoE', $obj->getTipo()->getId());
        $statement->bindValue(':idSector', $obj->getSector()->getId());
        $ret = $statement->execute();

        return $ret;
    }

}