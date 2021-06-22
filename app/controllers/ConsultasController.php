<?php

namespace Controllers;

# POPOS
require_once __DIR__ . '/../POPOs/Usuario.php';
require_once __DIR__ . '/../POPOs/TipoUsuario.php';
require_once __DIR__ . '/../POPOs/Sector.php';
require_once __DIR__ . '/../POPOs/Pedido.php';
require_once __DIR__ . '/../POPOs/PedidoProducto.php';
require_once __DIR__ . '/../POPOs/Producto.php';
require_once __DIR__ . '/../POPOs/Mesa.php';
require_once __DIR__ . '/../POPOs/Factura.php';
require_once __DIR__ . '/../POPOs/Comentario.php';
require_once __DIR__ . '/../POPOs/TipoComentario.php';
use POPOs\TipoUsuario as TU;
use POPOs\Usuario as U;
use POPOs\Sector as S;
use POPOs\Pedido as P;
use POPOs\PedidoProducto as PP;
use POPOs\Producto as Prod;
use POPOs\Mesa as M;
use POPOs\Factura as F;
use POPOs\Comentario as C;
use POPOs\TipoComentario as TC;

require_once __DIR__ . '/../db/DoctrineEntityManagerFactory.php';
use db\DoctrineEntityManagerFactory as DEMF;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Message\ResponseInterface as IResponse;

class ConsultasController {

    private static int $pedidoCancelado = 4;

    private static function retornarResponse ( IResponse $response, mixed $toCodified ) : IResponse {
        $ret = json_encode($toCodified, JSON_INVALID_UTF8_SUBSTITUTE);
        $response->getBody()->write($ret);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /* #region Usuarios */
    public static function fechaHorariosUsuarios ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $qb = DEMF::getQueryBuilder();
        $fechaIngresoUsuarios = $qb->select( 'u.nombre, u.apellido, u.fechaIngreso' )
                                ->from( U::class, 'u' )
                                ->getQuery()
                                ->execute();
        return self::retornarResponse($response, $fechaIngresoUsuarios);
    }

    public static function cantOperacionesCadaUnoPorSector ( IRequest $request, IResponse $response, array $args ) : IResponse {
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

    public static function cantOpXSector ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $qb = DEMF::getQueryBuilder();
        $cantOpXSector =    $qb->select( 'SUM(u.cantOperaciones) as cantidad, s.nombre as sector' )
                            ->from( U::class, 'u' )
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
                            ->setParameter( ':IdSector', intval( $args['idSector'] ) )
                            ->getQuery()
                            ->execute();
        return self::retornarResponse($response, $cantOpXSector);
    }
    /* #endregion */

    /* #region Pedidos */
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
        $masV = self::pedidoOrdenadoXV()[0];
        return self::retornarResponse($response, $masV);
    }

    public static function menosVendido ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $V = self::pedidoOrdenadoXV();
        $menosV = end($V);
        return self::retornarResponse($response, $menosV);
    }

    public static function pedidosEntregadosTarde ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $qb = DEMF::getQueryBuilder();
        $tardes =   $qb->select( 'pp' )
                    ->from( PP::class, 'pp' )
                    ->where(
                        $qb->expr()->lt( 'pp.horaFinEstipulada', 'pp.horaFin' )
                    )
                    ->getQuery()
                    ->execute();
        return self::retornarResponse($response, $tardes);
    }

    public static function cancelados ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $qb = DEMF::getQueryBuilder();
        $cancelados =   $qb->select( 'pp' )
                        ->from( PP::class, 'pp' )
                        ->where(
                            $qb->expr()->eq( 'pp.estado', self::$pedidoCancelado )
                        )
                        ->getQuery()
                        ->execute();
        return self::retornarResponse( $response, $cancelados );
    }
    /* #endregion Pedidos */

    /* #region Mesas */
    private static function usoMesas () : array {
        $qb = DEMF::getQueryBuilder();
        $usoMesas = $qb->select( 'COUNT(m) as veces_usada, m.id' )
                    ->from ( M::class, 'm' )
                    ->innerJoin( 
                        P::class, 
                        'p',
                        'WITH',
                        $qb->expr()->eq( 'p.mesa', 'm.id' )
                    )
                    ->groupBy( 'm.id' )
                    ->orderBy( 'veces_usada', 'DESC' )
                    ->getQuery()
                    ->execute();
        return $usoMesas;
    }

    public static function mesaMasUsada ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $mesaMasU = self::usoMesas()[0];
        return self::retornarResponse( $response, $mesaMasU );
    }

    public static function mesaMenosUsada ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $mesasU = self::usoMesas();
        $mesaMenosU = end( $mesasU );
        return self::retornarResponse( $response, $mesaMenosU );
    }

    private static function facturado () : array {
        $qb = DEMF::getQueryBuilder();
        $facturado =    $qb->select( 'm.id as mesa, SUM( prod.valor * pp.cantidad ) as facturado' )
                        ->from( M::class, 'm' )

                        ->innerJoin( 
                            P::class,
                            'p',
                            'WITH',
                            $qb->expr()->eq( 'm.id', 'p.mesa' )
                        )

                        ->innerJoin(
                            PP::class,
                            'pp',
                            'WITH',
                            $qb->expr()->eq( 'pp.pedido', 'p.codigo' )
                        )

                        ->innerJoin(
                            Prod::class,
                            'prod',
                            'WITH',
                            $qb->expr()->eq( 'pp.producto', 'prod.id' )
                        )

                        ->innerJoin(
                            F::class,
                            'f',
                            'WITH',
                            $qb->expr()->eq( 'f.pedido', 'p.codigo' )
                        )
                        ->groupBy( 'm.id' )
                        ->orderBy( 'facturado', 'DESC' )
                        ->getQuery()
                        ->execute();
        return $facturado;
    }

    public static function mesaMasFactura ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $mesaMasF = self::facturado()[0];
        return self::retornarResponse($response, $mesaMasF);
    }

    public static function mesaMenosFactura ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $mesaF = self::facturado();
        $mesaMenosF = end($mesaF);
        return self::retornarResponse($response, $mesaMenosF);
    }

    public static function mesaConFactura () : array {
        $qb = DEMF::getQueryBuilder();
        $mesasConFactura =  $qb->select( 'f.id as facturaId, m.id as mesaId, SUM( pp.cantidad * prod.valor ) as valor_factura' )
                            ->from( M::class, 'm' )
                            ->innerJoin(
                                P::class,
                                'p',
                                'WITH',
                                $qb->expr()->eq( 'p.mesa', 'm.id' )
                            )
                            ->innerJoin(
                                PP::class,
                                'pp',
                                'WITH',
                                $qb->expr()->eq( 'pp.pedido', 'p.codigo' )
                            )
                            ->innerJoin(
                                Prod::class,
                                'prod',
                                'WITH',
                                $qb->expr()->eq( 'pp.producto', 'prod.id' )
                            )
                            ->innerJoin(
                                F::class,
                                'f',
                                'WITH',
                                $qb->expr()->eq( 'f.pedido', 'p.codigo' )
                            )
                            ->groupBy( 'f.id' )
                            ->orderBy( 'valor_factura', 'DESC' )
                            ->getQuery()
                            ->execute();
        return $mesasConFactura;
    }

    public static function mesaConMayorFactura ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $mesaConMayorFactura = self::mesaConFactura()[0];
        return self::retornarResponse( $response, $mesaConMayorFactura );
    }

    public static function mesaConMenorFactura ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $mesasConFactura = self::mesaConFactura();
        $mesaConMenorFactura = end($mesasConFactura);
        return self::retornarResponse( $response, $mesaConMenorFactura );
    }

    private static function mesaPuntuacion () : array {
        $qb = DEMF::getQueryBuilder();
        $mesaPuntuacion =   $qb->select( 'm.id, c.puntuacion, c.comentario' )
                            ->from( M::class, 'm' )
                            ->innerJoin(
                                P::class,
                                'p',
                                'WITH',
                                $qb->expr()->eq( 'p.mesa', 'm.id' )
                            )
                            ->innerJoin(
                                C::class,
                                'c',
                                'WITH',
                                $qb->expr()->eq( 'c.pedido', 'p.codigo' )
                            )
                            ->groupBy( 'c.id' )
                            ->orderBy( 'c.puntuacion', 'DESC' )
                            ->getQuery()
                            ->execute();
        return $mesaPuntuacion;
    }

    public static function mesaMayorPuntuación ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $mesaMayorPuntuacion = self::mesaPuntuacion()[0];
        return self::retornarResponse( $response, $mesaMayorPuntuacion );
    }

    public static function mesaMenorPuntuacion ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $mesasPuntuacion = self::mesaPuntuacion();
        $mesaMenorPuntuacion = end( $mesasPuntuacion );
        return self::retornarResponse( $response, $mesaMenorPuntuacion );
    }
    /* #endregion */

}