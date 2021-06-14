<?php

namespace POPOs;

require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/TipoOperacion.php';
require_once __DIR__ . '/SectorOperacion.php';


/**
 * Representa una Operación de LABM.
 * @Entity
 * @Table(name="OperacionHistorial") 
 */
class OperacionHistorial implements \JsonSerializable {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ManyToOne(targetEntity="TipoOperacion")
     * @JoinColumn(name="operacion", referencedColumnName="id")
     */
    private ?TipoOperacion $operacion;

    /**
     * @ManyToOne(targetEntity="Usuario")
     * @JoinColumn(name="responsableId", referencedColumnName="id")
     */
    private ?Usuario $responsable;

    /**
     * @Column(type="datetime")
     */
    private ?\DateTimeInterface $fechaHora;

    /**
     * @Column
     */
    private bool $exito;

    /**
     * @ManyToOne(targetEntity="SectorOperacion")
     * @JoinColumn(name="sector", referencedColumnName="id")
     */
    private ?SectorOperacion $sector;

    public function __construct(
                                int $id = -1,
                                ?TipoOperacion $operacion = NULL,
                                ?Usuario $responsable = NULL,
                                ?\DateTimeInterface $fechaHora = NULL,
                                bool $exito = false,
                                ?SectorOperacion $sector = NULL)
    {
        $this->id = $id;
        $this->operacion = $operacion;
        $this->responsable = $responsable;
        $this->fechaHora = $fechaHora;
        $this->exito = $exito;
        $this->sector = $sector;
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
    public function getOperacion() : ?TipoOperacion
    {
        return $this->operacion;
    }

    /**
     * Set the value of operacion
     *
     * @return  self
     */ 
    public function setOperacion(?TipoOperacion $operacion) : self
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get the value of responsable
     */ 
    public function getResponsable() : ?Usuario
    {
        return $this->responsable;
    }

    /**
     * Set the value of responsable
     *
     * @return  self
     */ 
    public function setResponsable(?Usuario $responsable) : self
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get the value of fechaHora
     */ 
    public function getFechaHora() : ?\DateTimeInterface
    {
        return $this->fechaHora;
    }

    /**
     * Set the value of fechaHora
     *
     * @return  self
     */ 
    public function setFechaHora(?\DateTimeInterface $fechaHora)
    {
        $this->fechaHora = $fechaHora;

        return $this;
    }

    /**
     * Get the value of exito
     */ 
    public function getExito() : bool
    {
        return $this->exito;
    }

    /**
     * Set the value of exito
     *
     * @return  self
     */ 
    public function setExito(bool $exito)
    {
        $this->exito = $exito;

        return $this;
    }

    public function jsonSerialize() : mixed
    {
        $ret = array();
        if ( isset($this->id) )
            $ret["id"] = $this->id;
        $ret["operacion"] = $this->operacion;
        $ret["responsable"] = $this->responsable;
        $ret["fechaHora"] = $this->fechaHora->format( 'd-m-Y H:i:s' );
        $ret["exito"] = $this->exito;
        $ret["sector"] = $this->sector;
        return $ret;
    }

    /**
     * Get the value of sector
     */ 
    public function getSector() : ?SectorOperacion
    {
        return $this->sector;
    }

    /**
     * Set the value of sector
     *
     * @return  self
     */ 
    public function setSector(?SectorOperacion $sector)
    {
        $this->sector = $sector;

        return $this;
    }
}