<?php
namespace POPOs;

require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

use Doctrine\ORM\Mapping;
use interfaces\SerializeWithJSON as SWJ;
/**
 * Representa un Tipo de Usuario.
 * @Entity
 * @Table(name="TipoUsuario")
 */
class TipoUsuario implements SWJ
{

    /**
     * @Id
     * @Column(type="integer")
     */
    private int $id;

    /**
     * @Column(length=45)
     */
    private string $tipo;

    /**
     * @OneToOne(targetEntity="Sector")
     * @JoinColumn(name="sectorId", referencedColumnName="id", nullable=true)
     */
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
        $ret["sector"] = $this->sector;
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
        catch ( \JsonException ) {
            return NULL;
        }
        
    }

    private static function asssocToObj( array $assoc ) : ?self {
        $keysHasToHave = array_keys( (new TipoUsuario())->jsonSerialize() );
        $keysHasHave = array_keys( $assoc );
        
        if ( count( array_diff( $keysHasToHave, $keysHasHave ) ) > 0 ) return NULL;

        $id = intval($assoc['id']);
        $sector = $assoc['sector'];
        $ret = new TipoUsuario( $id,
                                $assoc['tipo'],
                                new Sector($sector) );
        return $ret;
    }
    
    public function __toString()
    {
        return json_encode( $this->jsonSerialize() );
    }

}
