<?php

require_once __DIR__ . '/../beans/EstadoMesa.php';
require_once __DIR__ . '/../db/AccesoDatos.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';

class EstadoMesaModel implements ICRUD{

    private static string $columnaId = 'Id';
    private static string $columnaEstado = 'Estado';

    private static function crearEstadosMesas (array $allAssoc) : array {
        $ret = array();
        
        foreach ( $allAssoc as $key => $assoc ) {
            $ret [$key] = self::crearEstadoMesa($assoc);
        }

        return $ret;
    }

    private static function crearEstadoMesa (array $assoc) : EstadoMesa {
        $Id = intval($assoc[self::$columnaId]);
        $estado = $assoc[self::$columnaEstado];

        return new EstadoMesa($Id, $estado);
    }

    public static function readById(mixed $id): mixed
    {
        $ret = NULL;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'SELECT 	EstadoMesa.Id, EstadoMesa.Estado
            FROM EstadoMesa
            WHERE EstadoMesa.Id = :id;'
        );
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $executed = $statement->execute();

        if ( $executed ) {
            $assoc = $statement->fetch(PDO::FETCH_ASSOC);
            $ret = ($assoc !== false) ? self::crearEstadoMesa($assoc) : $ret;
        }

        return $ret;
    }

    public static function readAllObjects(): array
    {
        $ret = array();
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'SELECT EstadoMesa.Id, EstadoMesa.Estado
            FROM EstadoMesa;'
        );
        $executed = $statement->execute();

        if ( $executed ) {
            $assoc = $statement->fetchAll(PDO::FETCH_ASSOC);
            $ret = ($assoc !== false) ? self::crearEstadosMesas($assoc) : $ret;
        }

        return $ret;
    }

    public static function insertObject(mixed $obj): bool
    {
        $ret = false;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            'INSERT INTO EstadoMesa (EstadoMesa.Estado)
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
                FROM EstadoMesa
                WHERE EstadoMesa.Id = :id;'
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
            'UPDATE EstadoMesa
            SET EstadoMesa.Estado = :estado
            WHERE EstadoMesa.Id = :id;'
        );
        $statement->bindValue(':id', $obj->getId(), PDO::PARAM_INT);
        $statement->bindValue(':estado', $obj->getEstado());
        $ret = $statement->execute();

        return $ret;
    }
}