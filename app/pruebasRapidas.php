<?php

require_once __DIR__ . '/POPOs/TipoComentario.php';
require_once __DIR__ . '/db/DoctrineEntityManagerFactory.php';
require_once __DIR__ . '/POPOs/EstadoMesa.php';
require_once __DIR__ . '/POPOs/PedidoEstado.php';
require_once __DIR__ . '/POPOs/Sector.php';

use POPOs\TipoComentario as TC;
use POPOs\EstadoMesa as EM;
use POPOs\PedidoEstado as PE;
use POPOs\Sector as S;

use db\DoctrineEntityManagerFactory as DEMF;
use GuzzleHttp\Psr7\Response;


$app->get ( '/pruebaTC', function () {
    $TCRep = DEMF::getEntityManager()->getRepository(TC::class);
    
    return new Response( 200, [], $TCRep->find(1) );
} );

$app->get ( '/pruebaEM', function() {
    $TCRep = DEMF::getEntityManager()->getRepository(EM::class);

    return new Response( 200, [], $TCRep->find(1) );
} );

$app->get ( '/pruebaPE', function() {
    $TCRep = DEMF::getEntityManager()->getRepository(PE::class);

    return new Response(200, [], $TCRep->find(1));
} );

$app->get( '/pruebaS', function () {
    $TCRep = DEMF::getEntityManager()->getRepository(S::class);

    return new Response(200, [], $TCRep->find(1));
} );