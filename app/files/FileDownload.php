<?php

namespace Files;

use Slim\Psr7\Stream;

require_once __DIR__ . '/IArchivoDescarga.php';

/**
 * @Deprecated
 */
class FileDownload implements IArchivoDescarga {

    private IArchivoDescarga $creador;

    public function __construct( IArchivoDescarga $creador )
    {
        $this->creador = $creador;
    }

    public function crearArchivo(array $arrayDatos): Stream
    {
        return $this->creador->crearArchivo($arrayDatos);
    }

}