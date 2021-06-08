<?php

require_once __DIR__ . '/configuration.php';

use Slim\Factory\AppFactory;
use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

// Settear el directorio donde se van a subir las imágenes de un Pedido:
$containerBuilder = new ContainerBuilder();
$container = $containerBuilder->build();

$container->set('imagenes_pedidos', __DIR__ . '/img/Pedidos');

// Instantiate App
$basePath = str_replace( '/index.php', '', $_SERVER['PHP_SELF'] );
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->setBasePath( $basePath );

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Rutteo:
require_once __DIR__ . '/routes/autoRoute.php';

$app->run();