<?php

namespace Controllers;

require_once __DIR__ . '/CRUDAbstractController.php';
require_once __DIR__ . '/../models/PedidoProductoModel.php';
require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../POPOs/PedidoProducto.php';
require_once __DIR__ . '/../db/DoctrineEntityManagerFactory.php';
require_once __DIR__ . '/../POPOs/Producto.php';

use Models\PedidoProductoModel as PPM;
use Models\ProductoModel as PM;
use Models\PedidoModel as PeM;
use POPOs\Producto as Prod;
use POPOs\PedidoProducto as PP;
use POPOs\Pedido as P;
use db\DoctrineEntityManagerFactory as DEMF;
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
        
        $objs = NULL;
        
        $parsedRequestBody = json_decode( $request->getBody()->__toString(), true );
        $tipo = $parsedRequestBody['usuario']['tipo'];
        
        if ( $tipo['id'] === 1 ) {
            $objs = self::queryPedidosConCodigo()
                    ->setParameter(':codigo', $args['codigoPedido'])
                    ->getQuery()->execute();
        }
        else {
            
            $objs = self::queryResponsable()
                    ->setParameter(':idResponsable', $parsedRequestBody['usuario']['id'])->getQuery()->execute();
            
            if ( empty($objs) ) {
                $objs = self::queryNoTomadaSector()
                        ->setParameter( ':codigo', $args['codigoPedido'] )
                        ->setParameter( ':sector', $tipo['sector']['id'] )
                        ->getQuery()->execute();
            }
            
        }
        
        $jsonObjs = json_encode( $objs, JSON_INVALID_UTF8_SUBSTITUTE );

        $response->getBody()->write($jsonObjs);
        return $response->withAddedHeader( 'Content-Type', 'application/json' );
    }

    private static function queryPedidosConCodigo() {
        $qb = DEMF::getQueryBuilder();
        return $qb->select( 'pp' )
                ->from(PP::class, 'pp')
                ->innerJoin(P::class, 'p')
                ->where( 
                    $qb->expr()->eq( 'p.codigo', ':codigo' )
                );
    }

    private static function queryResponsable() {
        $qb = DEMF::getQueryBuilder();
        return $qb->select('pp')
                ->from(PP::class, 'pp')
                ->where(
                    $qb->expr()->eq( 'pp.responsable', ':idResponsable' )
                );
    }

    private static function queryNoTomadaSector() {
        $qb = DEMF::getQueryBuilder();
        return $qb->select( 'pp' )
                ->from( PP::class, 'pp' )
                ->innerJoin(Prod::class, 'prod', 'WITH', $qb->expr()->eq( 'prod.id', 'pp.producto' ))
                ->innerJoin(P::class, 'p', 'WITH', $qb->expr()->eq( 'pp.pedido', 'p.codigo' ))
                ->where(
                    $qb->expr()->andX(
                        $qb->expr()->eq( 'p.codigo', ':codigo' ),
                        $qb->expr()->eq( 'prod.sector', ':sector' ),
                        $qb->expr()->eq( 'pp.estado', 1 )
                    )
                );
    }

}