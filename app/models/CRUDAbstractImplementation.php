<?php

namespace Models;

require_once __DIR__ . '/../db/DoctrineEntityManagerFactory.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';

use db\DoctrineEntityManagerFactory as DEMF;
use Doctrine\ORM\EntityRepository;
use interfaces\ICRUD;

abstract class CRUDAbstractImplementation implements ICRUD {

    private EntityRepository $er;
    private string $className;

    protected function __construct( string $className ) {
        
        if ( !class_exists($className) ) throw new \Exception( "No existe la clase", -458 );

        $this->er = DEMF::getEntityManager()->getRepository( $className );
        $this->className = $className;
    }

    protected function getEntityRepository () : EntityRepository {
        return $this->er;
    }

    public function readById (mixed $id): mixed
    {
        try {
            return $this->er->find($id);
        } catch ( \Throwable ) {
            return NULL;
        }
    }

    public function readAllObjects(): array
    {
        try {
            return $this->er->findAll();
        } catch ( \Throwable ) {
            return NULL;
        }
    }

    public function insertObject(mixed $obj): bool
    {
        try {
            DEMF::getEntityManager()->persist($obj);
            DEMF::getEntityManager()->flush();
            return true;
        } catch ( \Throwable ) {
            return false;   
        }
    }

    public function deleteById(mixed $id): mixed
    {
        try {
            $entity = $this->er->find($id);
            if ( $entity === NULL ) throw new \Exception();

            DEMF::getEntityManager()->remove($entity);
            DEMF::getEntityManager()->flush($entity);
            return $entity;
        } catch ( \Throwable ) {
            return NULL;
        }
    }
    
    public function updateObject(mixed $obj): bool
    {
        try {
            DEMF::getEntityManager()->flush($obj);
            return true;
        } catch ( \Throwable ) {
            return false;
        }
    }
}