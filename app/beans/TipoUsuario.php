<?php

require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

/**
 * Representa un Tipo de Usuario.
 */
class TipoUsuario implements SerializeWithJSON
{

    private int $id;
    private string $tipo;
    private ?Sector $sector;

    public function __construct(
        int $id = -1, 
        string $tipo = '',
        ?Sector $sector = NULL)
    {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->sector = $sector;
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of tipo
     */
    public function getTipo() : string
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */
    public function setTipo(string $tipo) : self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of sector
     */ 
    public function getSector() : ?Sector
    {
        return $this->sector;
    }

    /**
     * Set the value of sector
     *
     * @return  self
     */ 
    public function setSector(?Sector $sector) : self
    {
        $this->sector = $sector;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["tipo"] = $this->tipo;
        $ret["sectorId"] = ( $this->sector !== NULL ) ? $this->sector->getId() : -1;
        return $ret;
    }

    /**
     * Convierte de json a TipoUsuario.
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
        $keysHasToHave = array_keys( (new TipoUsuario())->jsonSerialize() );
        $keysHasHave = array_keys( $assoc );
        
        if ( count( array_diff( $keysHasToHave, $keysHasHave ) ) > 0 ) return NULL;

        $id = intval($assoc['id']);
        $sector = intval($assoc['sectorId']);
        $ret = new TipoUsuario( $id,
                                $assoc['tipo'],
                                new Sector($sector) );
        return $ret;
    }
    
}
