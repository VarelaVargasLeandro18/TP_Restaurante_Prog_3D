<?php

namespace Files;

require_once __DIR__ . '/IArchivoCarga.php';
require_once __DIR__ . '/FileLoad.php';

class CSVFileLoad extends FileLoad implements IArchivoCarga  {

    private string $separator;
    private string $formDataName;

    public function __construct(
        string $formDataName,
        string $separator = ','
    )
    {
        $this->formDataName = $formDataName;
        $this->separator = $separator;
    }

    public function obtenerDatosDeArchivo(array $nombreAtributosEnOrden): array {
        $pathArchivo = self::verificarYobtenerPathArchivo($this->formDataName);
        
        if ( $pathArchivo === NULL ) return array();


        $ret = array();

        if ( ( $archivo = fopen( $pathArchivo, "r" ) ) !== false ) {

            while ( ( $data = fgetcsv( $archivo, 0, $this->separator ) ) !== false ) {
                $count = count($data);
                $aGuardar = array();

                if ( $count != count($nombreAtributosEnOrden) ) {
                    fclose($archivo);
                    return array();
                }

                for ( $index = 0; $index < $count; $index++ ) {
                    $aGuardar[ $nombreAtributosEnOrden[$index] ] = $data[$index];
                }

                array_push( $ret, $aGuardar );

            }

            fclose($archivo);

        }

        return $ret;

    }

}