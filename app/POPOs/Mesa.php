<?php

namespace POPOs;


/**
 * Representa una Mesa del local.
 * @Entity
 * @Table(name="Mesa")
 */
class Mesa implements \JsonSerializable
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
}