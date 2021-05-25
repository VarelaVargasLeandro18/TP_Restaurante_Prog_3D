<?php

require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

/**
 * Representa un Sector del local
 */
class Sector implements SerializeWithJSON
{

    private int $id;
    private string $nombre;

    public function __construct(int $id, string $nombre)
    {
        $this->id = $id;
        $this->nombre = $nombre;
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
     * Get the value of nombre
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function jsonSerialize()
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["nombre"] = $this->nombre;
        return $ret;
    }

    public static function decode ( string $serialized ) : mixed {
        
        try {
            $assoc = json_decode($serialized, true, 512, JSON_THROW_ON_ERROR);
        
            $id = intval($assoc['id']);
            $nombre = $assoc['nombre'];
            $ret = new Sector( $id, $nombre );
            return $ret;
        }
        catch ( JsonException $ex ) {
            return NULL;
        }

    }

}
