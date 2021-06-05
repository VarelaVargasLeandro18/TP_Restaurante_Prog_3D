<?php

require_once __DIR__ . '/POPOs/TipoComentario.php';
require_once __DIR__ . '/db/DoctrineEntityManagerFactory.php';
require_once __DIR__ . '/POPOs/EstadoMesa.php';
require_once __DIR__ . '/POPOs/PedidoEstado.php';
require_once __DIR__ . '/POPOs/Sector.php';
require_once __DIR__ . '/POPOs/Mesa.php';
require_once __DIR__ . '/POPOs/TipoUsuario.php';
require_once __DIR__ . '/POPOs/Usuario.php';

use POPOs\TipoComentario as TC;
use POPOs\EstadoMesa as EM;
use POPOs\PedidoEstado as PE;
use POPOs\Sector as S;
use POPOs\Mesa;
use POPOs\TipoUsuario as TU;
use POPOs\Usuario as U;

use db\DoctrineEntityManagerFactory as DEMF;
use GuzzleHttp\Psr7\Response;

$app->get ( '/pruebaTC', function () {
    $TCRep = DEMF::getEntityManager()->getRepository(TC::class);
    
    return new Response( 200, [], $TCRep->find(1) );
} );

$app->get ( '/pruebaEM', function() {
    $EMRep = DEMF::getEntityManager()->getRepository(EM::class);

    return new Response( 200, [], $EMRep->find(1) );
} );

$app->get ( '/pruebaPE', function() {
    $PERep = DEMF::getEntityManager()->getRepository(PE::class);

    return new Response(200, [], $PERep->find(1));
} );

$app->get( '/pruebaS', function () {
    $SRep = DEMF::getEntityManager()->getRepository(S::class);

    return new Response(200, [], $SRep->find(1));
} );

$app->get( '/pruebaM', function() {
    $MRep = DEMF::getEntityManager()->getRepository(Mesa::class);

    return new Response(200, [], $MRep->find("aaaa1"));
} );

$app->get( '/pruebaTU', function () {
    $TURep = DEMF::getEntityManager()->getRepository(TU::class);

    return new Response(200, [], $TURep->find(2));
} );

$app->get( '/pruebaU', function () {
    $URep = DEMF::getEntityManager()->getRepository(U::class);

    return new Response(200, array( 'Content-type' => 'application/json' ), $URep->find(8));
} );