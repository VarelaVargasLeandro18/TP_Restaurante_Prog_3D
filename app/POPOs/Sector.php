<?php

namespace POPOs;
require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

use interfaces\SerializeWithJSON as SWJ;
use Doctrine\ORM\Mapping;

/**
 * Representa un Sector del local
 * @Entity
 * @Table(name="Sector")
 */
class Sector implements SWJ
{

    /**
     * @Id
     * @Column(type="integer")
     */
    private int $id;

    /**
     * @Column(length=45)
     */
    private string $nombre;

    public function __construct(
                                int $id = -1, 
                                string $nombre = '')
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
            return self::assocToObj($assoc);
        }
        catch ( \Exception ) {
            return NULL;
        }

    }

    private static function assocToObj ( array $assoc ) : ?self {
        $keysHasToHave = array_keys( (new Sector())->jsonSerialize() );
        $keysHasHave = array_keys( $assoc );
        
        if ( count( array_diff( $keysHasToHave, $keysHasHave ) ) > 0 ) return NULL;

        $id = intval($assoc['id']);
        $ret = new Sector( $id,
                                $assoc['nombre'] );
        return $ret;

    }

    public function __toString()
    {
        return json_encode( $this->jsonSerialize() );
    }

}
