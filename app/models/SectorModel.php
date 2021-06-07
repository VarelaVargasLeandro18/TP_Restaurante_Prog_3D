<?php

namespace Models;

require_once __DIR__ . '/../POPOs/Sector.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

use POPOs\Sector;
use Models\CRUDAbstractModel;

class SectorModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( Sector::class );
    }

    private function __clone()
    {}

}