<?php

require_once __DIR__ . '/../beans/Empleado.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';
require_once __DIR__ . '/../db/AccesoDatos.php';
require_once __DIR__ . '/SectorModel.php';
require_once __DIR__ . '/TipoEmpleadoModel.php';
require_once __DIR__ . '/PermisosEmpleadoSectorModel.php';

class EmpleadoModel implements ICRUD {

    private static string $columnaId = 'Id';
    private static string $columnaNombre = 'Nombre';
    private static string $columnaApellido = 'Apellido';
    private static string $columnaSectorId = 'SectorId';
    private static string $columnaTipoEmpleadoId = 'TipoEmpleadoId';
    private static string $columnaUsuario = 'Usuario';
    private static string $columnaContraseniaHash = 'Contrasenia_hash';

    private static function crearEmpleados(array $allAssoc) : array {
        $ret = array();
        
        foreach ( $allAssoc as $key => $assoc ) {
            $ret [$key] = self::crearEmpleado($assoc);
        }

        return $ret;
    }

    private static function crearEmpleado(array $assoc) : Empleado {
        $ret = NULL;
        $id = intval($assoc[self::$columnaId]);
        $nombre = $assoc[self::$columnaNombre];
        $apellido = $assoc[self::$columnaApellido];
        $sectorId = intval($assoc[self::$columnaSectorId]);
        $sector = SectorModel::readById($sectorId);
        $tipoId = intval($assoc[self::$columnaTipoEmpleadoId]);
        $tipo = TipoEmpleadoModel::readById($tipoId);
        $usuario = $assoc[self::$columnaUsuario];
        $contraseniaHash = $assoc[self::$columnaContraseniaHash];

        return new Empleado(
            $id,
            $nombre,
            $apellido,
            $sector,
            $tipo,
            $usuario,
            $contraseniaHash
        );
    }

    public static function readById(mixed $id): mixed
    {
        $ret = NULL;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            '
            SELECT 
                Empleado.Id,
                Empleado.Nombre,
                Empleado.Apellido,
                Empleado.SectorId,
                Empleado.TipoEmpleadoId,
                Empleado.Usuario,
                Empleado.Contrasenia_hash
            FROM Empleado
            WHERE Empleado.Id = :id;
            '
        );
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $executed = $statement->execute();
        
        if ( $executed ) {
            $assoc = $statement->fetch(PDO::FETCH_ASSOC);
            $ret = ($assoc !== false) ? self::crearEmpleado($assoc) : $ret;
        }

        return $ret;
    }

    public static function readAllObjects(): array
    {
        $ret = array();
        $access= AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            '
            SELECT 
                Empleado.Id,
                Empleado.Nombre,
                Empleado.Apellido,
                Empleado.SectorId,
                Empleado.TipoEmpleadoId,
                Empleado.Usuario,
                Empleado.Contrasenia_hash
            FROM Empleado;
            '
        );
        $executed = $statement->execute();

        if ( $executed ) {
            $allAssoc = $statement->fetchAll(PDO::FETCH_ASSOC);
            $ret = ($allAssoc !== false) ? self::crearEmpleados($allAssoc) : $ret; 
        }

        return $ret;
    }

    public static function insertObject(mixed $obj): bool
    {
        $ret = false;
        $check = self::readByUsrPass( $obj->getUsuario(), $obj->getContraseniaHash() );

        if ( $check == NULL ) {
            $access = AccesoDatos::obtenerInstancia();
            $statement = $access->prepararConsulta(
                '
                INSERT INTO Empleado (
                    Empleado.Nombre,
                    Empleado.Apellido,
                    Empleado.SectorId,
                    Empleado.TipoEmpleadoId,
                    Empleado.Usuario,
                    Empleado.Contrasenia_hash
                )
                VALUES (
                    :nombre, 
                    :apellido, 
                    :sectorId, 
                    :tipoId, 
                    :usuario, 
                    :ctrsnia
                );
                '
            );
            $statement->bindValue(':nombre', $obj->getNombre());
            $statement->bindValue(':apellido', $obj->getApellido());
            $statement->bindValue(':sectorId', $obj->getSector()->getId(), PDO::PARAM_INT);
            $statement->bindValue(':tipoId', $obj->getTipo()->getId(), PDO::PARAM_INT);
            $statement->bindValue(':usuario', $obj->getUsuario());
            $statement->bindValue(':ctrsnia', $obj->getContraseniaHash());
            
            if ( PermisosEmpleadoSectorModel::comprobarTipoSector(
                        $obj->getSector()->getId(),
                        $obj->getTipo()->getId()) ) {
                $ret = $statement->execute();
            }
            else
                $ret = false;
        }

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
                FROM Empleado
                WHERE Empleado.Id = :id
                '
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
            UPDATE 
                Empleado
            SET
                Empleado.Nombre = :nombre,
                Empleado.Apellido = :apellido,
                Empleado.SectorId = :sectorId,
                Empleado.TipoEmpleadoId = :tipoId,
                Empleado.Usuario = :usuario,
                Empleado.Contrasenia_hash = :ctrsnia
            WHERE Empleado.Id = :id;
            '
        );
        $statement->bindValue(':id', $obj->getId(), PDO::PARAM_INT);
        $statement->bindValue(':nombre', $obj->getNombre());
        $statement->bindValue(':apellido', $obj->getApellido());
        $statement->bindValue(':sectorId', $obj->getSector()->getId(), PDO::PARAM_INT);
        $statement->bindValue(':tipoId', $obj->getTipo()->getId(), PDO::PARAM_INT);
        $statement->bindValue(':usuario', $obj->getUsuario());
        $statement->bindValue(':ctrsnia', $obj->getContraseniaHash());
        
        if ( PermisosEmpleadoSectorModel::comprobarTipoSector(
            $obj->getSector()->getId(),
            $obj->getTipo()->getId()) ) {
            $ret = $statement->execute();
        }
        else
            $ret = false;

        return $ret;
    }

    // FUNCIONES PROPIAS
    public static function readByUsrPass (string $usuario, string $contrasenia) : mixed {
        $ret = NULL;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            '
            SELECT 
                Empleado.Id,
                Empleado.Nombre,
                Empleado.Apellido,
                Empleado.SectorId,
                Empleado.TipoEmpleadoId,
                Empleado.Usuario,
                Empleado.Contrasenia_hash
            FROM Empleado
            WHERE Empleado.Usuario = :usuario AND Empleado.Contrasenia_hash = :cntr_hash;
            '
        );
        $statement->bindValue(':usuario', $usuario);
        $statement->bindValue(':cntr_hash', $contrasenia);
        $executed = $statement->execute();

        if ( $executed ) {
            $assoc = $statement->fetch(PDO::FETCH_ASSOC);
            $ret = ($assoc !== false) ? self::crearEmpleado($assoc) : $ret;
        }

        return $ret;
    }

}