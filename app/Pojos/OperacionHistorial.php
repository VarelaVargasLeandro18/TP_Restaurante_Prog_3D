<?php

require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

class OperacionHistorial implements SerializeWithJSON {

    private int $id;
    private string $operacion;
    private ?Usuario $responsable;
    private string $fechaHora;

    public function __construct(
                                int $id = -1,
                                string $operacion = '',
                                ?Usuario $responsable = NULL,
                                string $fechaHora = '')
    {
        $this->id = $id;
        $this->operacion = $operacion;
        $this->responsable = $responsable;
        $this->fechaHora = $fechaHora;
    }

    /**
     * Get the value of id
     */ 
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId(int $id) : self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of operacion
     */ 
    public function getOperacion() : string
    {
        return $this->operacion;
    }

    /**
     * Set the value of operacion
     *
     * @return  self
     */ 
    public function setOperacion(string $operacion) : self
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get the value of responsable
     */ 
    public function getResponsable() : Usuario
    {
        return $this->responsable;
    }

    /**
     * Set the value of responsable
     *
     * @return  self
     */ 
    public function setResponsable(Usuario $responsable) : self
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get the value of fechaHora
     */ 
    public function getFechaHora() : string
    {
        return $this->fechaHora;
    }

    /**
     * Set the value of fechaHora
     *
     * @return  self
     */ 
    public function setFechaHora(string $fechaHora)
    {
        $this->fechaHora = $fechaHora;

        return $this;
    }

    public function jsonSerialize() : mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["operacion"] = $this->operacion;
        $ret["responsableId"] = ( $this->responsable !== NULL ) ? $this->responsable->getId() : -1;
        $ret["fechaHora"] = $this->fechaHora;
        return $ret;
    }

    /**
     * Convierte de json a Pedido.
     */
    public static function decode ( string $serialized ) : mixed {
        
        try {
            $assoc = json_decode($serialized, true, 512, JSON_THROW_ON_ERROR);
            return self::asssocToObj($assoc);
        }
        catch ( JsonException ) {
            return NULL;
        }
        
    }

    private static function asssocToObj( array $assoc ) : ?self {
        $keysHasToHave = array_keys( (new OperacionHistorial())->jsonSerialize() );
        $keysHasHave = array_keys( $assoc );
        
        if ( count( array_diff( $keysHasToHave, $keysHasHave ) ) > 0 ) return NULL;

        $id = intval($assoc["id"]);
        $responsable = intval($assoc["responsableId"]);
        $ret = new OperacionHistorial(
                                        $id,
                                        $assoc["operacion"],
                                        new Usuario($responsable),
                                        $assoc["fechaHora"]
        );

        return $ret;
    }

}