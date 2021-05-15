<?php

require_once __DIR__ . '/../beans/PedidoEstado.php';
require_once __DIR__ . '/../db/AccesoDatos.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';

class PedidoEstadoModel implements ICRUD{

    private static string $columnaId = 'Id';
    private static string $columnaEstado = 'Estado';

    private static function crearPedidosEstados (array $allAssoc) : array {
        $ret = array();
        
        foreach ( $allAssoc as $key => $assoc ) {
            $ret [$key] = self::crearPedidoEstado($assoc);
        }

        return $ret;
    }

    private static function crearPedidoEstado (array $assoc) : PedidoEstado {
        $Id = intval($assoc[self::$columnaId]);
        $estado = $assoc[self::$columnaEstado];

        return new PedidoEstado($Id, $estado);
    }

    public static function readById(mixed $id): mixed
    {
        $ret = NULL;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'SELECT 	PedidoEstado.Id, PedidoEstado.Estado
            FROM PedidoEstado
            WHERE PedidoEstado.Id = :id;'
        );
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $executed = $statement->execute();

        if ( $executed ) {
            $assoc = $statement->fetch(PDO::FETCH_ASSOC);
            $ret = ($assoc !== false) ? self::crearPedidoEstado($assoc) : NULL;
        }

        return $ret;
    }

    public static function readAllObjects(): array
    {
        $ret = array();
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'SELECT PedidoEstado.Id, PedidoEstado.Estado
            FROM PedidoEstado;'
        );
        $executed = $statement->execute();

        if ( $executed ) {
            $assoc = $statement->fetchAll(PDO::FETCH_ASSOC);
            $ret = ($assoc !== false) ? self::crearPedidosEstados($assoc) : NULL;
        }

        return $ret;
    }

    public static function insertObject(mixed $obj): bool
    {
        $ret = false;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'INSERT INTO PedidoEstado (PedidoEstado.Estado)
            VALUES (:estado);'
        );
        $statement->bindValue(':estado', $obj->getEstado());
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
                FROM PedidoEstado
                WHERE PedidoEstado.Id = :id;'
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
            'UPDATE PedidoEstado
            SET PedidoEstado.Estado = :estado
            WHERE PedidoEstado.Id = :id;'
        );
        $statement->bindValue(':id', $obj->getId(), PDO::PARAM_INT);
        $statement->bindValue(':estado', $obj->getEstado());
        $ret = $statement->execute();

        return $ret;
    }
}