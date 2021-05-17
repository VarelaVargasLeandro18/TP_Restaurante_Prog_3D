<?php

require_once __DIR__ . '/../beans/Producto.php';
require_once __DIR__ . '/SectorModel.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';
require_once __DIR__ . '/../db/AccesoDatos.php';

class ProductoModel implements ICRUD {

    private static string $columnaId = 'Id';
    private static string $columnaNombre = 'Nombre';
    private static string $columnaTipo = 'Tipo';
    private static string $columnaSectorId = 'SectorId';

    private static function crearProductos (array $allAssoc) : array {
        $ret = array();
        
        foreach ( $allAssoc as $key => $assoc ) {
            $ret [$key] = self::crearProducto($assoc);
        }

        return $ret;
    }

    public static function crearProducto (array $assoc) : Producto {
        $id = intval($assoc[self::$columnaId]);
        $nombre = $assoc[self::$columnaNombre];
        $tipo = $assoc[self::$columnaTipo];
        $sectorId = intval($assoc[self::$columnaSectorId]);
        $sector = SectorModel::readById($sectorId);

        return new Producto($id, $nombre, $tipo, $sector);
    }

    public static function readById(mixed $id): mixed
    {
        $ret = NULL;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            '
            SELECT 
                Producto.Id,
                Producto.Nombre,
                Producto.Tipo,
                Producto.SectorId
            FROM Producto
            WHERE Producto.Id = :id;
            '
        );
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $executed = $statement->execute();

        if ( $executed ) {
            $assoc = $statement->fetch(PDO::FETCH_ASSOC);
            $ret = ($assoc !== false) ? self::crearProducto($assoc) : $ret;
        }
        return $ret;
    }

    public static function readAllObjects(): array
    {
        $ret = NULL;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            '
            SELECT 
                Producto.Id,
                Producto.Nombre,
                Producto.Tipo,
                Producto.SectorId
            FROM Producto;
            '
        );
        $executed = $statement->execute();

        if ( $executed ) {
            $allAssoc = $statement->fetchAll(PDO::FETCH_ASSOC);
            $ret = ($allAssoc !== false) ? self::crearProductos($allAssoc) : $ret;
        }
        return $ret;
    }

    public static function insertObject(mixed $obj): bool
    {
        $ret = false;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            '
            INSERT INTO Producto (
                Producto.Nombre,
                Producto.Tipo,
                Producto.SectorId
            )
            VALUES (:nombre, :tipo, :sectorId);
            '
        );
        $statement->bindValue(':nombre', $obj->getNombre());
        $statement->bindValue(':tipo', $obj->getTipo());
        $statement->bindValue(':sectorId', $obj->getSector()->getId(), PDO::PARAM_INT);
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
                FROM Producto
                WHERE Producto.Id = :id;
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
            UPDATE Producto 
            SET
                Producto.Nombre = :nombre,
                Producto.Tipo = :tipo,
                Producto.SectorId = :sectorId
            WHERE Producto.Id = :id;
            '
        );
        $statement->bindValue(':nombre', $obj->getNombre());
        $statement->bindValue(':tipo', $obj->getTipo());
        $statement->bindValue(':sectorId', $obj->getSector()->getId(), PDO::PARAM_INT);
        $statement->bindValue(':id', $obj->getId());
        $ret = $statement->execute();

        return $ret;
    }

}