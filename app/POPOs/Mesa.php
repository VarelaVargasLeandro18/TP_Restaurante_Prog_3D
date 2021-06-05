<?php

namespace POPOs;
require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

use interfaces\SerializeWithJSON as SWJ;
use Doctrine\ORM\Mapping;

/**
 * Representa una Mesa del local.
 * @Entity
 * @Table(name="Mesa")
 */
class Mesa implements SWJ
{

    /**
     * @Id
     * @Column(type="string")
     */
    private string $id;

    /**
     * @ManyToOne(targetEntity="EstadoMesa")
     * @JoinColumn(name="estado_id", referencedColumnName="id", nullable=false)
     */
    private ?EstadoMesa $estado;

    public function __construct(
                                string $id = '', 
                                ?EstadoMesa $estado = NULL )
    {
        $this->id = $id;
        $this->estado = $estado;
    }

    /**
     * Get the value of id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of estado
     */
    public function getEstado(): ?EstadoMesa
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */
    public function setEstado(?EstadoMesa $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function jsonSerialize() : mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["estado"] = $this->estado;
        return $ret;
    }

    /**
     * Convierte de json a Mesa.
     */
    public static function decode ( string $serialized ) : mixed {
        
        try {
            $assoc = json_decode($serialized, true, 512, JSON_THROW_ON_ERROR);
            return self::asssocToObj($assoc);
        }
        catch ( \JsonException ) {
            return NULL;
        }
        
    }

    private static function asssocToObj( array $assoc ) : ?self {
        $keysHasToHave = array_keys( (new Mesa())->jsonSerialize() );
        $keysHasHave = array_keys( $assoc );
        
        if ( count( array_diff( $keysHasToHave, $keysHasHave ) ) > 0 ) return NULL;

        $id = intval($assoc["id"]);
        $estado = $assoc["estado"];
        $ret = new Mesa($id,
                        new EstadoMesa($estado)
        );

        return $ret;
    }

    public function __toString()
    {
        return json_encode( $this->jsonSerialize() );
    }

}