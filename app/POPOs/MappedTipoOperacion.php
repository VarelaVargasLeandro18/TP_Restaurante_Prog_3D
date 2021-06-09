<?php

namespace POPOs;

/**
 * @MappedSuperclass
 */
class MappedTipoOperacion implements \JsonSerializable {

    /**
     * @Id
     * @Column(type="integer")
     */
    private int $id;

    /**
     * @Column
     */
    private string $operacion;

    public function __construct(
        int $id = -1,
        string $operacion = ''
    )
    {
        $this->id = $id;
        $this->operacion = $operacion;
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
    public function setId(int $id)
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
    public function setOperacion(string $operacion)
    {
        $this->operacion = $operacion;

        return $this;
    }

    public function jsonSerialize()
    {
        $ret['id'] = $this->id;
        $ret['operacion'] = $this->operacion;
        return $ret;
    }

}