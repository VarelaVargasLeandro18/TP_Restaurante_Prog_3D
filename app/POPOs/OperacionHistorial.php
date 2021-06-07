<?php

namespace POPOs;

require_once __DIR__ . '/Usuario.php';

/**
 * Representa una Operación de LABM.
 * @Entity
 * @Table(name="OperacionHistorial") 
 */
class OperacionHistorial implements \JsonSerializable {

    /**
     * @Id
     * @Column(type="integer")
     */
    private int $id;

    /**
     * @Column(length=45)
     */
    private string $operacion;

    /**
     * @ManyToOne(targetEntity="Usuario")
     * @JoinColumn(name="responsableId", referencedColumnName="id")
     */
    private ?Usuario $responsable;

    /**
     * @Column
     */
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
        $ret["responsable"] = $this->responsable;
        $ret["fechaHora"] = $this->fechaHora;
        return $ret;
    }
}