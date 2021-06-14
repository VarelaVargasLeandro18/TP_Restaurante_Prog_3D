<?php

namespace Files;

use Slim\Psr7\Stream;

require_once __DIR__ . '/IArchivoDescarga.php';

class CSVDownload implements IArchivoDescarga {

    private string $path;

    public function __construct( string $path = __DIR__ . '/../created_files/archivo.csv' )
    {
        $this->path = $path;
    }

    public function crearArchivo(array $arrayDatos): Stream
    {
        $csv = $this->crearCSV($arrayDatos);
        file_put_contents($this->path, $csv);
        return new Stream( fopen($this->path, 'r') );
    }

    private function crearCSV(array $arrayDatos) : string {
        $ret = "";

        foreach ( $arrayDatos as $dato ) {

            $strCol = "";

            foreach ( $dato as $columna ) {
                if ( $columna instanceof \DateTimeInterface ) 
                    $columnaEncoded = $columna->format('Y-m-d H:i:s');
                else
                    $columnaEncoded = json_encode($columna, true);

                $strCol .= $columnaEncoded . ",";
            }

            $ret .= substr( $strCol, 0, ( strrpos(",", $strCol) - 1 ) );
            $ret .= "\n";

        }

        return $ret;
    }

    /**
     * Get the value of path
     */ 
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * Set the value of path
     *
     * @return  self
     */ 
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }
}