<?php

namespace POPOs;

require_once __DIR__ . '/TipoComentario.php';
require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/Pedido.php';

/**
 * Representa un comentario realizado por un cliente al restaurante.
 * @Entity
 * @Table(name="Comentario")
 */
class Comentario implements \JsonSerializable {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ManyToOne(targetEntity="TipoComentario")
     * @JoinColumn(name="tipoComentarioId", referencedColumnName="id")
     */
    private ?TipoComentario $tipo;

    /**
     * @ManyToOne(targetEntity="Usuario")
     * @JoinColumn(name="clienteId", referencedColumnName="id")
     */
    private ?Usuario $cliente;
    
    /**
     * @Column(type="integer")
     */
    private int $puntuacion;

    /**
     * @Column(type="string")
     */
    private string $comentario;

    /**
     * @ManyToOne(targetEntity = "Pedido")
     * @JoinColumn(name = "pedido", referencedColumnName = "codigo")
     */
    private ?Pedido $pedido;

    public function __construct(
                                int $id = -1,
                                ?TipoComentario $tipo = NULL,
                                ?Usuario $cliente = NULL,
                                int $puntuacion = 0,
                                string $comentario = '',
                                ?Pedido $pedido = NULL
    )
    {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->cliente = $cliente;
        $this->puntuacion = $puntuacion;
        $this->comentario = $comentario;
        $this->pedido = $pedido;
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
    public function getTipo() : ?TipoComentario
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */ 
    public function setTipo(?TipoComentario $tipo) : self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of cliente
     */ 
    public function getCliente() : ?Usuario
    {
        return $this->cliente;
    }

    /**
     * Set the value of cliente
     *
     * @return  self
     */ 
    public function setCliente(?Usuario $cliente) : self
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get the value of puntuacion
     */ 
    public function getPuntuacion() : int
    {
        return $this->puntuacion;
    }

    /**
     * Set the value of puntuacion
     *
     * @return  self
     */ 
    public function setPuntuacion(int $puntuacion) : self
    {
        $this->puntuacion = $puntuacion;

        return $this;
    }

    /**
     * Get the value of comentario
     */ 
    public function getComentario() : string
    {
        return $this->comentario;
    }

    /**
     * Set the value of comentario
     *
     * @return  self
     */ 
    public function setComentario(string $comentario) : self
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get the value of pedido
     */ 
    public function getPedido() : ?Pedido
    {
        return $this->pedido;
    }

    /**
     * Set the value of pedido
     *
     * @return  self
     */ 
    public function setPedido(?Pedido $pedido)
    {
        $this->pedido = $pedido;

        return $this;
    }

    public function jsonSerialize() : mixed
    {
        $ret = array();
        if ( isset($this->id) )
            $ret["id"] = $this->id;
        $ret["tipo"] = $this->tipo;
        $ret["cliente"] = $this->cliente !== NULL;
        $ret["puntuacion"] = $this->puntuacion;
        $ret["comentario"] = $this->comentario;
        return $ret;
    }
}