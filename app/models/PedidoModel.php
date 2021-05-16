<?php

require_once __DIR__ . '/ProductoModel.php';
require_once __DIR__ . '/MesaModel.php';
require_once __DIR__ . '/PedidoEstadoModel.php';
require_once __DIR__ . '/../beans/Pedido.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';
require_once __DIR__ . '/../db/AccesoDatos.php';

class PedidoModel implements ICRUD {

    private static string $columnaId = 'Id';
    private static string $columnaProductoId = 'ProductoId';
    private static string $columnaMesaId = 'MesaId';
    private static string $columnaPedidoEstadoId = 'PedidoEstadoId';

    private static function crearPedidos (array $allAssoc) : array {
        $ret = array();
        
        foreach ( $allAssoc as $key => $assoc ) {
            $ret [$key] = self::crearPedido($assoc);
        }

        return $ret;
    }
    
    private static function crearPedido (array $assoc) : mixed {
        $id = $assoc[self::$columnaId];
        $ProductoId = intval($assoc[self::$columnaProductoId]);
        $producto = ProductoModel::readById($ProductoId);
        $MesaId = $assoc[self::$columnaMesaId];
        $mesa = MesaModel::readById($MesaId);
        $PedidoEstadoId = intval($assoc[self::$columnaPedidoEstadoId]);
        $estado = PedidoEstadoModel::readById($PedidoEstadoId);
        
        return new Pedido( $id, $producto, $mesa, $estado );
    }

    public static function readById(mixed $id): mixed
    {
        $ret = NULL;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            '
            SELECT 
                Pedido.Id,
                Pedido.ProductoId,
                Pedido.MesaId,
                Pedido.PedidoEstadoId
            FROM Pedido
            WHERE Pedido.Id = :id;  
            '
        );
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $executed = $statement->execute();

        if ( $executed ) {
            $assoc = $statement->fetch(PDO::FETCH_ASSOC);
            $ret = ($assoc !== false) ? self::crearPedido($assoc) : false;
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
                Pedido.Id,
                Pedido.ProductoId,
                Pedido.MesaId,
                Pedido.PedidoEstadoId
            FROM Pedido;
            '
        );
        $executed = $statement->execute();
        
        if ( $executed ) {
            $allAssoc = $statement->fetchAll(PDO::FETCH_ASSOC);
            $ret = ($allAssoc !== false) ? self::crearPedidos($allAssoc) : false;
        }
        return $ret;
    }

    public static function insertObject(mixed $obj): bool
    {
        $ret = false;
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta(
            '
            INSERT INTO Pedido (
                Pedido.Id,
                Pedido.ProductoId,
                Pedido.MesaId,
                Pedido.PedidoEstadoId
            )
            VALUES (
                :id,
                :productoId,
                :mesaId,
                :pedidoEstadoId
            )  
            '
        );
        $statement->bindValue(':id', $obj->getId());
        $statement->bindValue(':productoId', $obj->getProducto()->getId(), PDO::PARAM_INT);
        $statement->bindValue(':mesaId', $obj->getMesa()->getId());
        $statement->bindValue(':pedidoEstadoId', $obj->getEstado()->getId(), PDO::PARAM_INT);
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
                FROM Pedido
                WHERE Pedido.Id = :id;  
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
            UPDATE Pedido
            SET 
                Pedido.ProductoId = :productoId,
                Pedido.MesaId = :mesaId,
                Pedido.PedidoEstadoId = :pedidoEstadoId
            WHERE Pedido.Id = :id;
            '
        );
        $statement->bindValue(':id', $obj->getId());
        $statement->bindValue(':productoId', $obj->getProducto()->getId(), PDO::PARAM_INT);
        $statement->bindValue(':mesaId', $obj->getMesa()->getId());
        $statement->bindValue(':pedidoEstadoId', $obj->getEstado()->getId(), PDO::PARAM_INT);
        $ret = $statement->execute();
        return $ret;
    }

}