<?php

namespace Controllers;

require_once __DIR__ . '/CRUDAbstractController.php';
require_once __DIR__ . '/../models/PedidoProductoModel.php';
require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../POPOs/PedidoProducto.php';
require_once __DIR__ . '/../db/DoctrineEntityManagerFactory.php';

use Models\PedidoProductoModel as PPM;
use Models\ProductoModel as PM;
use Models\PedidoModel as PeM;
use POPOs\PedidoProducto as PP;
use POPOs\Pedido as P;
use db\DoctrineEntityManagerFactory as DEMF;
use POPOs\PedidoProducto;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class PedidoProductoController extends CRUDAbstractController {

    protected static string $modelName = PPM::class;
    protected static string $nombreClase = 'PedidoProducto';
    protected static int $PK_type = 0;

    protected static ?array $jsonConfig = array( 
        'CodigoPedido' => '', 
        'IdProducto' => '',
        'cantidad' => ''
    );

    private function __construct()
    {}

    private function __clone()
    {}

    protected static final function createObject(array $array): mixed
    {
        $pem = new PeM();
        $pm = new PM();

        $pe = $pem->readById( $array['CodigoPedido'] );
        $p = $pm->readById( $array['IdProducto'] );
        
        return new PP(
            0,
            $pe,
            $p,
            $array['cantidad']
        );
    }

    protected static final function updateObject(array $array, mixed $objBD): mixed
    {
        $obj = self::createObject($array);
        $objBD->setPedido($obj->getPedido());
        $objBD->setProducto($obj->getProducto());
        $obj->setCantidad($obj->getCantidad());

        return $objBD;
    }

    public static function obtenerProductosDePedido (Request $request, Response $response, array $args) {
        $qb = DEMF::getQueryBuilder();
        $objs = $qb->select( 'pp' )
                    ->from(PP::class, 'pp')
                    ->innerJoin(P::class, 'p')
                    ->where( 
                        $qb->expr()->eq( 'p.codigo', ':codigo' )
                    )
                    ->setParameter(':codigo', $args['codigoPedido'])
                    ->getQuery()->execute();
        
        $jsonObjs = json_encode( $objs, JSON_INVALID_UTF8_SUBSTITUTE );

        $response->getBody()->write($jsonObjs);
        return $response->withAddedHeader( 'Content-Type', 'application/json' );
    }

}