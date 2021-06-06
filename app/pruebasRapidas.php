<?php

require_once __DIR__ . '/POPOs/TipoComentario.php';
require_once __DIR__ . '/db/DoctrineEntityManagerFactory.php';
require_once __DIR__ . '/POPOs/EstadoMesa.php';
require_once __DIR__ . '/POPOs/PedidoEstado.php';
require_once __DIR__ . '/POPOs/Sector.php';
require_once __DIR__ . '/POPOs/Mesa.php';
require_once __DIR__ . '/POPOs/TipoUsuario.php';
require_once __DIR__ . '/POPOs/Usuario.php';
require_once __DIR__ . '/POPOs/Producto.php';
require_once __DIR__ . '/POPOs/PermisoEmpleadoSector.php';
require_once __DIR__ . '/POPOs/PedidoHistorial.php';
require_once __DIR__ . '/POPOs/OperacionHistorial.php';
require_once __DIR__ . '/POPOs/Factura.php';
require_once __DIR__ . '/POPOs/Comentario.php';

use POPOs\TipoComentario as TC;
use POPOs\EstadoMesa as EM;
use POPOs\PedidoEstado as PE;
use POPOs\Sector as S;
use POPOs\Mesa;
use POPOs\TipoUsuario as TU;
use POPOs\Usuario as U;
use POPOs\Producto as P;
use POPOs\PermisoEmpleadoSector as PES;
use POPOs\PedidoHistorial as PH;
use POPOs\OperacionHistorial as OH;
use POPOs\Factura as F;
use POPOs\Comentario as C;

use db\DoctrineEntityManagerFactory as DEMF;
use GuzzleHttp\Psr7\Response;

$app->get ( '/pruebaTC', function () {
    $TCRep = DEMF::getEntityManager()->getRepository(TC::class);
    $ret = json_encode($TCRep->findAll());
    
    return new Response( 200, array( 'Content-type' => 'application/json' ), $ret );
} );

$app->get ( '/pruebaEM', function() {
    $EMRep = DEMF::getEntityManager()->getRepository(EM::class);
    $ret = json_encode($EMRep->findAll());

    return new Response( 200, array( 'Content-type' => 'application/json' ), $ret );
} );

$app->get ( '/pruebaPE', function() {
    $PERep = DEMF::getEntityManager()->getRepository(PE::class);
    $ret = json_encode($PERep->findAll());

    return new Response(200, array( 'Content-type' => 'application/json' ), $ret);
} );

$app->get( '/pruebaS', function () {
    $SRep = DEMF::getEntityManager()->getRepository(S::class);
    $ret = json_encode($SRep->findAll());

    return new Response(200, array( 'Content-type' => 'application/json' ), $ret);
} );

$app->get( '/pruebaM', function() {
    $MRep = DEMF::getEntityManager()->getRepository(Mesa::class);
    $ret = json_encode($MRep->findAll());

    return new Response(200, array( 'Content-type' => 'application/json' ), $ret);
} );

$app->get( '/pruebaTU', function () {
    $TURep = DEMF::getEntityManager()->getRepository(TU::class);
    $ret = json_encode($TURep->findAll());

    return new Response(200, array( 'Content-type' => 'application/json' ), $ret);
} );

$app->get( '/pruebaU', function () {
    $URep = DEMF::getEntityManager()->getRepository(U::class);
    $ret = json_encode($URep->findAll());

    return new Response(200, array( 'Content-type' => 'application/json' ), $ret);
} );

$app->get( '/pruebaP', function ()  {
    $PRep = DEMF::getEntityManager()->getRepository(P::class);
    $ret = json_encode($PRep->findAll());

    return new Response(200, array( 'Content-type' => 'application/json' ), $ret);
} );

$app->get( '/pruebaPES', function () {
    $PESRep = DEMF::getEntityManager()->getRepository(PES::class);
    $ret = json_encode($PESRep->findAll());

    return new Response(200, array('Content-type' => 'application/json'), $ret);
} );

$app->get( '/pruebaPH', function () {
    $PHRep = DEMF::getEntityManager()->getRepository(PH::class);
    $ret = json_encode($PHRep->findAll());

    return new Response(200, array('Content-type' => 'application/json'), $ret);
} );

$app->get( '/pruebaOH', function () {
    $OHRep = DEMF::getEntityManager()->getRepository(OH::class);
    $ret = json_encode($OHRep->findAll());

    return new Response(200, array('Content-type' => 'application/json'), $ret);
} );

$app->get( '/pruebaF', function () {
    $FRep = DEMF::getEntityManager()->getRepository(F::class);
    $ret = json_encode($FRep->findAll());

    return new Response(200, array('Content-type' => 'application/json'), $ret);
} );

$app->get( '/pruebaC', function () {
    $CRep = DEMF::getEntityManager()->getRepository(C::class);
    $ret = json_encode($CRep->findAll());

    return new Response(200, array('Content-type' => 'application/json'), $ret);
} );