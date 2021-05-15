<?php

require_once __DIR__ . '/../beans/EstadoMesa.php';
require_once __DIR__ . '/../db/AccesoDatos.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';

class EstadoMesaModel implements ICRUD{

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