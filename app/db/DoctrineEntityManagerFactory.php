<?php

namespace db;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;

class DoctrineEntityManagerFactory
{
    private static ?EntityManager $em = NULL;

    private function __construct(){}

    private static function createEntityManager() : void {
        
        try {
            $paths = array ( __DIR__ . '/../POPOs/' );
            self::$em = EntityManager::create(
                array(
                    'driver' => "pdo_mysql",
                    'user' => $_ENV['MYSQL_USER'],
                    'password' => $_ENV['MYSQL_PASS'],
                    'dbname' => $_ENV['MYSQL_DB'],
                    'host' => $_ENV['MYSQL_HOST'],
                    'port' => $_ENV['MYSQL_PORT']
                ),
                Setup::createAnnotationMetadataConfiguration($paths, false)
            );
        }
        catch ( ORMException $ex ) {
            throw new \RuntimeException( $ex );
        }

    }

    public static function getEntityManager() : ?EntityManager {
        if ( self::$em === NULL )
            self::createEntityManager();
        
        return self::$em;
    }

    public function __clone()
    {
        trigger_error('ERROR: La clonación de este objeto no está permitida', E_USER_ERROR);
    }

}