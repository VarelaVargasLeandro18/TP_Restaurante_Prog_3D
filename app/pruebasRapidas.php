<?php

require_once __DIR__ . '/Pojos/TipoComentario.php';
require_once __DIR__ . '/db/DoctrineEntityManagerFactory.php';


use Model\TipoComentario as TC;
use db\DoctrineEntityManagerFactory as DEMF;
use GuzzleHttp\Psr7\Response;


$app->get ( '/pruebaTC', function () {
    $TCRep = DEMF::getEntityManager()->getRepository(TC::class);
    
    return new Response( 200, [], $TCRep->find(1) );
} );