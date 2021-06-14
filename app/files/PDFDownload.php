<?php

namespace Files;

use Slim\Psr7\Stream;
use Dompdf\Dompdf;

require_once __DIR__ . '/IArchivoDescarga.php';

class PDFDownload implements IArchivoDescarga {

    private string $path;
    private Dompdf $dompdf;

    public function __construct( string $path = __DIR__ . '/../created_files/archivo.pdf' )
    {
        $this->path = $path;
    }

    public function crearArchivo(array $arrayDatos): Stream
    {
        $dompdf = new Dompdf();
        $dompdf->setPaper('A3', "landscape");
        $html = $this->generarHTML($arrayDatos);
        $dompdf->loadHtml($html, "UTF-8");
        $dompdf->render();
        $contenido = $dompdf->output();
        $this->dompdf = $dompdf;
        file_put_contents($this->path, $contenido);
        return new Stream( fopen($this->path, 'r') );
    }

    public function generarDescarga() {
        $this->dompdf->stream($this->path);
    }

    private function generarHTML ( array $arrayDatos ) : string {
        $html = '<table style="border: 1px solid black;border-collapse: collapse;">';

        foreach ( $arrayDatos as $dato ) {
            
            $html .= '<tr style="border: 1px solid black;">';

            foreach ( $dato as $columna ) {

                if ( $columna instanceof \DateTimeInterface ) 
                    $columnaEncoded = $columna->format('Y-m-d H:i:s');
                else
                    $columnaEncoded = json_encode($columna, true);

                $html .= "<td style=\"border:1px solid black;\">$columnaEncoded</td>";
            }

            $html .= "</tr>";

        }

        $html .= "</table>";
        return $html;
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