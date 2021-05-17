<?php

require_once __DIR__ . '/../beans/Mesa.php';
require_once __DIR__ . '/EstadoMesaModel.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';
require_once __DIR__ . '/../db/AccesoDatos.php';

class MesaModel implements ICRUD {

    private static string $columnaId = 'Id';
    private static string $columnaEstadoId = 'EstadoMesaId';

    private static function crearMesas (array $allAssoc) : array {
        $ret = array();
        
        foreach ( $allAssoc as $key => $assoc ) {
            $ret [$key] = self::crearMesa($assoc);
        }

        return $ret;
    }

    public static function crearMesa (array $assoc) : Mesa {
        $id = $assoc[self::$columnaId];
        $estadoId = intval($assoc[self::$columnaEstadoId]);
        $estado = EstadoMesaModel::readById($estadoId);
        
        return new Mesa ($id, $estado);
    }

    public static function readById(mixed $id): mixed
    {
        $ret = NULL;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            '
            SELECT 
                Mesa.Id,
                Mesa.EstadoMesaId
            FROM Mesa
            WHERE Mesa.Id = :id;
            '
        );
        $statement->bindValue(':id', $id);
        $executed = $statement->execute();

        if ( $executed ) {
            $assoc = $statement->fetch(PDO::FETCH_ASSOC);
            $ret = ($assoc !== false) ? self::crearMesa($assoc) : $ret;
        }
        return $ret;
    }

    public static function readAllObjects(): array
    {
        $ret = array();
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            '
            SELECT 
                Mesa.Id,
                Mesa.EstadoMesaId
            FROM Mesa;
            '
        );
        $executed = $statement->execute();

        if ( $executed ) {
            $allAssoc = $statement->fetchAll(PDO::FETCH_ASSOC);
            $ret = ($allAssoc !== false) ? self::crearMesas($allAssoc) : $ret;
        }
        return $ret;
    }

    public static function insertObject(mixed $obj): bool
    {
        $ret = false;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            '
            INSERT INTO Mesa (Mesa.Id, Mesa.EstadoMesaId)
            VALUES (:Id, :estadoId);
            '
        );
        $statement->bindValue(':Id', $obj->getId());
        $statement->bindValue(':estadoId', $obj->getEstado()->getId(), PDO::PARAM_INT);
        
        if ( EstadoMesaModel::readById($obj->getEstado()->getId()) !== NULL )
            $ret = $statement->execute();
        
        return $ret;
    }

    public static function deleteById(mixed $id): mixed
    {
        $ret = self::readById($id);

        if ($ret !== NULL) {
            $access = AccesoDatos::obtenerInstancia();
            $statement = $access->prepararConsulta(
                '
                DELETE
                FROM Mesa
                WHERE Mesa.Id = :id;
                '
            );
            $statement->bindValue(':id', $id);
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
            UPDATE Mesa
            SET Mesa.EstadoMesaId = :estadoId
            WHERE Mesa.Id = :id;
            '
        );
        $statement->bindValue(':id', $obj->getId());
        $statement->bindValue(':estadoId', $obj->getEstado()->getId(), PDO::PARAM_INT);
        $ret = $statement->execute();
        return $ret;
    }

}