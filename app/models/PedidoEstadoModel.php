<?php

require_once __DIR__ . '/../beans/PedidoEstado.php';
require_once __DIR__ . '/../db/AccesoDatos.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';

class PedidoEstadoModel implements ICRUD{

    private static string $columnaId = 'Id';
    private static string $columnaEstado = 'Estado';

    private static function crearEstadosMesas (array $allAssoc) : array {
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
        return $ret;
    }

    public static function readAllObjects(): array
    {
        $ret = array();
        return $ret;
    }

    public static function insertObject(mixed $obj): bool
    {
        $ret = false;
        return $ret;
    }

    public static function deleteById(mixed $id): mixed
    {
        $ret = NULL;
        return $ret;
    }

    public static function updateObject(mixed $obj): bool
    {
        $ret = false;
        return $ret;
    }
}