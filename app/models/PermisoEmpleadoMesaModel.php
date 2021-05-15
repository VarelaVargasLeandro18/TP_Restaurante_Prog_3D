<?php

require_once __DIR__ . '/../beans/PermisoEmpleadoMesa.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';
require_once __DIR__ . '/../db/AccesoDatos.php';
require_once __DIR__ . '/TipoEmpleadoModel.php';
require_once __DIR__ . '/EstadoMesaModel.php';

class PermisoEmpleadoMesaModel implements ICRUD {

    private static string $columnaId = 'Id';
    private static string $columnaTipoE = 'TipoEmpleadoId';
    private static string $columnaEMesa = 'EstadoMesaId';

    private static function crearPermisosEmpleadosSector ( array $allAssoc ) : array {
        $ret = array();
        
        foreach ( $allAssoc as $key => $assoc ) {
            $ret [$key] = self::crearPermisoEmpleadoMesa($assoc);
        }

        return $ret;
    }
    
    private static function crearPermisoEmpleadoMesa(array $assoc) : PermisoEmpleadoMesa {
        $Id = intval($assoc[self::$columnaId]);
        $idTipoE = intval($assoc[self::$columnaTipoE]);
        $idEM = intval($assoc[self::$columnaEMesa]);

        $estadoMesa = EstadoMesaModel::readById($idEM);
        $tipoE = TipoEmpleadoModel::readById($idTipoE);

        return new PermisoEmpleadoMesa($Id, $tipoE, $estadoMesa);
    }

    public static function readById(mixed $id): mixed
    {
        $ret = NULL;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'SELECT 	PermisosEmpleadoMesa.Id, PermisosEmpleadoMesa.TipoEmpleadoId, PermisosEmpleadoMesa.EstadoMesaId
            FROM PermisosEmpleadoMesa
            WHERE PermisosEmpleadoMesa.Id = :id;'
        );
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $executed = $statement->execute();

        if ( $executed ) {
            $assoc = $statement->fetch(PDO::FETCH_ASSOC);
            $ret = ($assoc !== false) ? self::crearPermisoEmpleadoMesa($assoc) : NULL;
        }

        return $ret;
    }

    public static function readAllObjects(): array
    {
        $ret = array();
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'SELECT 	PermisosEmpleadoMesa.Id, PermisosEmpleadoMesa.TipoEmpleadoId, PermisosEmpleadoMesa.EstadoMesaId
            FROM PermisosEmpleadoMesa;'
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
            INSERT INTO PermisosEmpleadoMesa (PermisosEmpleadoMesa.TipoEmpleadoId, PermisosEmpleadoMesa.EstadoMesaId)
                    VALUES (:idTipoE, :idEstadoMesa);'
        );
        $statement->bindValue(':idTipoE', $obj->getTipoEmpleado()->getId());
        $statement->bindValue(':idEstadoMesa', $obj->getEstadoMesa()->getId());
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
                FROM PermisosEmpleadoMesa
                wHERE PermisosEmpleadoMesa.Id = :id;'
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
            UPDATE PermisosEmpleadoMesa
            SET PermisosEmpleadoMesa.TipoEmpleadoId = :idTipoE, PermisosEmpleadoMesa.EstadoMesaId = :idEstadoMesa
            WHERE PermisosEmpleadoMesa.Id = :id;'
        );
        $statement->bindValue(':id', $obj->getId(), PDO::PARAM_INT);
        $statement->bindValue(':idTipoE', $obj->getTipoEmpleado()->getId());
        $statement->bindValue(':idEstadoMesa', $obj->getEstadoMesa()->getId());
        $ret = $statement->execute();

        return $ret;
    }

}