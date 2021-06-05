<?php
namespace POPOs;
require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

use interfaces\SerializeWithJSON as SWJ;
use Doctrine\ORM\Mapping;

/**
 * Representa un Producto que brinda un Sector determinado del local.
 * @Entity
 * @Table(name="Producto")
 */
class Producto implements SWJ
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

    /**
     * @Column(length=45)
     */
    private string $tipo;

    /**
     * @ManyToOne(targetEntity="Sector")
     * @JoinColumn(name="sectorId", referencedColumnName="id")
     */
    private ?Sector $sector;

    /**
     * @Column(type="decimal", scale=2, precision=2)
     */
    private float $valor;

    public function __construct(
                                int $id = -1, 
                                string $nombre = '', 
                                string $tipo = '', 
                                ?Sector $sector = NULL,
                                float $valor = 0)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->sector = $sector;
        $this->valor = $valor;
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

    /**
     * Get the value of tipo
     */
    public function getTipo(): string
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */
    public function setTipo(string $tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of sector
     */
    public function getSector(): Sector
    {
        return $this->sector;
    }

    /**
     * Set the value of sector
     *
     * @return  self
     */
    public function setSector(Sector $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get the value of valor
     */ 
    public function getValor() : float
    {
        return $this->valor;
    }

    /**
     * Set the value of valor
     *
     * @return  self
     */ 
    public function setValor(float $valor)
    {
        $this->valor = $valor;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["nombre"] = $this->nombre;
        $ret["tipo"] = $this->tipo;
        $ret["sector"] = $this->sector;
        $ret["valor"] = number_format($this->valor, 2, ',', '.');
        return $ret;
    }

    /**
     * Convierte de json a Producto.
     */
    public static function decode ( string $serialized ) : mixed {
        
        try {
            $assoc = json_decode($serialized, true, 512, JSON_THROW_ON_ERROR);
            return self::asssocToObj($assoc);
        }
        catch ( \Exception ) {
            return NULL;
        }
        
    }

    private static function asssocToObj( array $assoc ) : ?self {
        $keysHasToHave = array_keys( (new Producto())->jsonSerialize() );
        $keysHasHave = array_keys( $assoc );
        
        if ( count( array_diff( $keysHasToHave, $keysHasHave ) ) > 0 ) return NULL;

        $id = intval($assoc['id']);
        $sector = intval($assoc['sectorId']);
        $valor = floatval( $assoc['valor'] );
        $ret = new Producto( 
                            $id,
                            $assoc['nombre'],
                            $assoc['tipo'],
                            new Sector( $sector ),
                            $valor );

        return $ret;
    }

    public function __toString()
    {
        return json_encode($this->jsonSerialize());
    }
}
