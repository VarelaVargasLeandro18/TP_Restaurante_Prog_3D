<?php

namespace POPOs;

/** 
    * Representa un tipo de comentario que puede realizar un cliente.
    * @Entity
    * @Table (name="TipoComentario")
*/
class TipoComentario implements \JsonSerializable {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @Column(length=45)
     */
    private string $tipo;

    public function __construct(    
                                int $id = -1,
                                string $tipo = '')
    {
        $this->id = $id;
        $this->tipo = $tipo;
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

    public function jsonSerialize() : mixed
    {
        $ret = array();
        if( isset($this->id) )
            $ret["id"] = $this->id;
        $ret["tipo"] = $this->tipo;
        return $ret;
    }
}