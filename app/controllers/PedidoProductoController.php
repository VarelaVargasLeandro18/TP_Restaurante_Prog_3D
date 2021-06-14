<?php

namespace Controllers;

# POPOs
require_once __DIR__ . '/../POPOs/Usuario.php';
require_once __DIR__ . '/../POPOs/PedidoProducto.php';
require_once __DIR__ . '/../POPOs/Producto.php';
require_once __DIR__ . '/../POPOs/PedidoHistorial.php';
use POPOs\Producto as Prod;
use POPOs\PedidoProducto as PP;
use POPOs\Pedido as P;
use POPOs\PedidoHistorial as PH;


# Models
require_once __DIR__ . '/../models/PedidoProductoModel.php';
require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/PedidoEstadoModel.php';
use Models\PedidoProductoModel as PPM;
use Models\ProductoModel as PM;
use Models\PedidoModel as PeM;
use Models\UsuarioModel as UM;
use Models\PedidoEstadoModel as PEstadoM;

# Files
require_once __DIR__ . '/../files/PDFDownload.php';
require_once __DIR__ . '/../files/CSVDownload.php';
use Files\PDFDownload as PDF;
use Files\CSVDownload as CSV;

# OTROS
require_once __DIR__ . '/CRUDAbstractController.php';
require_once __DIR__ . '/../db/DoctrineEntityManagerFactory.php';
use db\DoctrineEntityManagerFactory as DEMF;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Fig\Http\Message\StatusCodeInterface as SCI;


class PedidoProductoController extends CRUDAbstractController {

    protected static string $modelName = PPM::class;
    protected static string $nombreClase = 'PedidoProducto';
    protected static int $PK_type = 0;

    protected static ?array $jsonConfig = array( 
        'CodigoPedido' => '', 
        'IdProducto' => '',
        'cantidad' => ''
    );

    private static int $pedidoPendiente = 1;
    private static int $pedidoEnPreparacion = 2;
    private static int $pedidoListo = 3;

    private function __construct()
    {}

    private function __clone()
    {}

    protected static final function createObject(array $array): mixed
    {
        $pem = new PeM();
        $pm = new PM();
        $estado = (new PEstadoM())->readById(self::$pedidoPendiente);

        $pe = $pem->readById( $array['CodigoPedido'] );
        $p = $pm->readById( $array['IdProducto'] );
        
        return (new PP(
            0,
            $pe,
            $p,
            $array['cantidad']
        ))->setEstado($estado);
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
                        $qb->expr()->eq( 'prod.sector', ':sector' ),
                        $qb->expr()->eq( 'pp.estado', 1 )
                    )
                );
    }

    public static function tomarPedido ( Request $request, Response $response, array $args ) : Response {
        $ppm = new PPM(); //PedidoProductoModel
        $um = new UM(); //UsuarioModel
        $pem = new PEstadoM();// PedidoEstadoModel
        
        $productoPedido = $ppm->readById( intval($args['id']) );

        if ( $productoPedido === NULL ) return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No se encontró el ' . self::$nombreClase );
        
        $bodyDecoded = json_decode($request->getBody()->__toString(), true);
        
        if ( !key_exists('agregarMinutos', $bodyDecoded) ) return $response->withStatus( SCI::STATUS_BAD_REQUEST, 'No se aclaran los minutos que se estima que tardará en realizarse el pedido.' );
        if ( $productoPedido->getResponsable() !== NULL ) return $response->withStatus( SCI::STATUS_UNAUTHORIZED, 'Este pedido está tomado.' );
        
        $horaInicio = new \DateTime();
        $minutosAgregar = $bodyDecoded['agregarMinutos'];
        $horaFinEstimada = (new \DateTime())->modify( "+{$minutosAgregar} minutes" );

        if ( $horaFinEstimada === false ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR, 'Error al procesar el timepo estimado de finalización.' );

        $usrDecoded = $bodyDecoded['usuario'];
        
        $responsable = $um->readById( $usrDecoded['id'] );

        if ( $productoPedido->getProducto()->getSector()->getId() !== $usrDecoded['tipo']['sector']['id'] ) return $response->withStatus( SCI::STATUS_UNAUTHORIZED, 'Este pedido no pertenece a su sector.' );

        $pe = $pem->readById(2);
        
        $productoPedido->setEstado($pe);
        $productoPedido->setResponsable( $responsable );
        $productoPedido->setHoraInicio( $horaInicio );
        $productoPedido->setHoraFinEstipulada( $horaFinEstimada );

        if ( !$ppm->updateObject( $productoPedido ) ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR, 'Error al actualizar el estado del pedido.' );

        return $response->withStatus( SCI::STATUS_OK, 'Se actualizó correctamente el pedido, el responsable es: ' . $responsable->getNombre() );
    }

    public static function terminarPedido ( Request $request, Response $response, array $args ) : Response {
        $ppm = new PPM(); //PedidoProductoModel
        $pem = new PEstadoM();// PedidoEstadoModel
        
        $productoPedido = $ppm->readById( intval($args['id']) );
        
        $bodyDecoded = json_decode($request->getBody()->__toString(), true);
        $usr = $bodyDecoded['usuario'];

        if ( $productoPedido === NULL ) return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No se encontró el ' . self::$nombreClase );
        if ( $productoPedido->getEstado()->getId() !== self::$pedidoEnPreparacion ) return $response->withStatus( SCI::STATUS_UNAUTHORIZED, 'El pedido no está registrado como en preparación.' );
        if ( $productoPedido->getResponsable()->getId() !== $usr['id'] ) return $response->withStatus( SCI::STATUS_UNAUTHORIZED, 'No eres el responsable de este pedido.' );

        $horaFinalizacion = new \DateTime();
        $pelisto = $pem->readById( self::$pedidoListo );
        $productoPedido->setHoraFin($horaFinalizacion);
        $productoPedido->setEstado($pelisto);

        if ( !$ppm->updateObject( $productoPedido ) ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR, 'No se pudo realizar un update del ' . self::$nombreClase );
        
        return $response->withStatus( SCI::STATUS_NO_CONTENT, 'Se ha actualizado el estado del ' . self::$nombreClase . ' a ' . $pelisto->getEstado() );
    }

    public static function realizarPedido ( Request $request, Response $response, array $args ) : Response {
        $bodyDecoded = json_decode($request->getBody()->__toString(), true);
        $pm = new PM();
        $pem = new PeM();
        $ppm = new PPM();
        $estado = (new PEstadoM())->readById(self::$pedidoPendiente);
        
        if ( ( $prod = $pm->readById($bodyDecoded['IdProducto']) ) === NULL ) return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No se encontró el producto solicitado.' );
        if ( ( $pedido = $pem->readById($bodyDecoded['IdPedido']) ) === NULL ) return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No se encontró el pedido solicitado.' );

        $pp = (new PP(
            0,
            $pedido,
            $prod,
            $bodyDecoded['Cantidad']
        ))->setEstado($estado);

        if ( !($ingresado = $ppm->insertObject($pp)) ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR, 'No se pudo insertar el Producto en el Pedido' );

        $response->getBody()->write( json_encode( array( 'id' => $ingresado->getId() ) ) );
        return $response;
    }

    public static function realizarEstadisticas ( Request $request, Response $response, array $args ) : Response {
        $format = 'Y-d-m';
        $qb = DEMF::getQueryBuilder();
        $treintaDiasQuery =  $qb->select( 'ph' )
                        ->from( PH::class, 'ph' )
                        ->where(
                            $qb->expr()->andX(
                                $qb->expr()->lte( 'ph.fechaCambio', ':fechaActual' ),
                                $qb->expr()->gte( 'ph.fechaCambio', ':fechamenostreinta' )
                            )
                        )
                        ->setParameter(':fechaActual', (new \DateTime())->format($format))
                        ->setParameter(':fechamenostreinta', (new \DateTime())->modify("-30 days")->format('Y-m-d'))
                        ->getQuery();
                        
        $treintaDias = $treintaDiasQuery->execute();
        $ret = json_encode($treintaDias, JSON_INVALID_UTF8_SUBSTITUTE);
        $response->getBody()->write($ret);
        return $response->withAddedHeader( 'Content-Type', 'application/json' );
    }

    public static function descargarTodosPDF ( Request $request, Response $response, array $args ) : Response {
        $pdf = new PDF();

        $ppm = new PPM();
        $pedidosProductos = $ppm->readAllObjects();
        $arrayDatos = array();

        foreach ( $pedidosProductos as $pp ) {
            array_push( $arrayDatos, $pp->jsonSerialize() );
        }
        
        $pdf->crearArchivo($arrayDatos);
        $pdf->generarDescarga();

        return $response;
    }

    public static function descargarTodosCSV ( Request $request, Response $response, array $args ) : Response {
        $csv = new CSV();

        $ppm = new PPM();
        $pedidosProductos = $ppm->readAllObjects();
        $arrayDatos = array();

        foreach ( $pedidosProductos as $pp ) {
            array_push( $arrayDatos, $pp->jsonSerialize() );
        }
        
        return $response
                ->withHeader('Content-Type', 'text/csv')
                ->withHeader('Content-Disposition', 'attachment; filenam="archivo.csv"')
                ->withBody( $csv->crearArchivo($arrayDatos) );
    }

}