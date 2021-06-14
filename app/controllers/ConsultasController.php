<?php

namespace Controllers;

# POPOS
require_once __DIR__ . '/../POPOs/Usuario.php';
require_once __DIR__ . '/../POPOs/TipoUsuario.php';
require_once __DIR__ . '/../POPOs/Sector.php';
require_once __DIR__ . '/../POPOs/Pedido.php';
require_once __DIR__ . '/../POPOs/PedidoProducto.php';
require_once __DIR__ . '/../POPOs/Producto.php';
use POPOs\TipoUsuario as TU;
use POPOs\Usuario as U;
use POPOs\Sector as S;
use POPOs\Pedido as P;
use POPOs\PedidoProducto as PP;
use POPOs\Producto as Prod;

require_once __DIR__ . '/../db/DoctrineEntityManagerFactory.php';
use db\DoctrineEntityManagerFactory as DEMF;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Message\ResponseInterface as IResponse;

class ConsultasController {

    private static function retornarResponse ( IResponse $response, mixed $toCodified ) : IResponse {
        $ret = json_encode($toCodified, JSON_INVALID_UTF8_SUBSTITUTE);
        $response->getBody()->write($ret);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function fechaHorariosUsuarios ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $qb = DEMF::getQueryBuilder();
        $fechaIngresoUsuarios = $qb->select( 'u.nombre, u.apellido, u.fechaIngreso' )
                                ->from( U::class, 'u' )
                                ->getQuery()
                                ->execute();
        return self::retornarResponse($response, $fechaIngresoUsuarios);
    }

    public static function cantOperacionesTodosPorSector ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $qb = DEMF::getQueryBuilder();
        $canOpXSector = $qb->select('u.cantOperaciones, u.nombre, u.apellido')
                        ->from(U::class, 'u')
                        ->innerJoin(
                            TU::class,
                            'tu',
                            'WITH',
                            $qb->expr()->eq( 'u.tipo', 'tu.id' )
                        )
                        ->innerJoin(
                            S::class,
                            's',
                            'WITH',
                            $qb->expr()->eq( 'tu.sector', 's.id' )
                        )
                        ->where(
                            $qb->expr()->eq( 's.id', ':IdSector' )
                        )
                        ->setParameter( ':IdSector', intval($args['idSector']) )
                        ->getQuery()
                        ->execute();
        return self::retornarResponse($response, $canOpXSector);
    }

    public static function cantOpCadaUno ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $qb = DEMF::getQueryBuilder();
        $cantOpXUno = $qb->select('u.cantOperaciones, u.nombre, u.apellido')
                        ->from( U::class, 'u' )
                        ->getQuery()->execute();
        return self::retornarResponse($response, $cantOpXUno);
    }

    private static function pedidoOrdenadoXV () : array {
        $qb = DEMF::getQueryBuilder();
        $ordenados =    $qb->select('SUM(pp.cantidad) as total, p.id, p.nombre')
                        ->from( PP::class, 'pp' )
                        ->innerJoin(
                            Prod::class,
                            'p',
                            'WITH',
                            'pp.producto = p.id'
                        )
                        ->groupBy( 'p.id' )
                        ->orderBy( 'total', 'DESC' )
                        ->getQuery()
                        ->execute();
        return $ordenados;
    }

    public static function masVendido  ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $masV = self::pedidoOrdenadoXV($args['codigo'])[0];
        return self::retornarResponse($response, $masV);
    }

    public static function menosVendido ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $V = self::pedidoOrdenadoXV($args['codigo']);
        $menosV = end($V);
        return self::retornarResponse($response, $menosV);
    }

}