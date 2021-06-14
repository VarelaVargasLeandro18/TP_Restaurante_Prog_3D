<?php

namespace POPOs;

require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/PedidoProducto.php';

/**
 * Representa un pedido cuyo estado ya ha cambiado y quieren guardarse sus estados anteriores.
 * @Entity
 * @Table(name="PedidoHistorial")
 */
class PedidoHistorial implements \JsonSerializable {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ManyToOne(targetEntity="PedidoProducto")
     * @JoinColumn(name="pedidoproducto", referencedColumnName="id")
     */
    private ?PedidoProducto $pedidoProducto;

    /**
     * @ManyToOne(targetEntity="Usuario")
     * @JoinColumn(name="responsableId", referencedColumnName="id")
     */
    private ?Usuario $responsable;

    /**
     * @ManyToOne(targetEntity="TipoOperacionPedido")
     * @JoinColumn(name="operacion", referencedColumnName="id")
     */
    private ?TipoOperacionPedido $operacion;

    /**
     * @Column(type="datetime")
     */
    private ?\DateTimeInterface $fechaCambio;

    public function __construct(
                                int $id = -1,
                                ?Usuario $responsable = NULL,
                                ?PedidoProducto $pedidoProducto = NULL,
                                ?TipoOperacionPedido $operacion = NULL,
                                ?\DateTimeInterface $fechaCambio = NULL
    )
    {
        $this->id = $id;
        $this->responsable = $responsable;
        $this->pedidoProducto = $pedidoProducto;
        $this->operacion = $operacion;
        $this->fechaCambio = $fechaCambio;
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
     * Get the value of pedidoProducto
     */ 
    public function getPedidoProducto() : ?PedidoProducto
    {
        return $this->pedidoProducto;
    }

    /**
     * Set the value of pedidoProducto
     *
     * @return  self
     */ 
    public function setPedidoProducto(?PedidoProducto $pedidoProducto)
    {
        $this->pedidoProducto = $pedidoProducto;

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

    public function jsonSerialize() : mixed
    {
        $ret = array();
        if( isset($this->id) )
            $ret["id"] = $this->id;
        $ret["pedidoProducto"] = $this->pedidoProducto;
        $ret["responsable"] = $this->responsable;
        $ret["operacion"] = $this->operacion;
        $ret["fechaCambio"] = $this->fechaCambio;
        $ret["operacion"] = $this->operacion;
        return $ret;
    }

    /**
     * Get the value of operacion
     */ 
    public function getOperacion() : ?TipoOperacionPedido
    {
        return $this->operacion;
    }

    /**
     * Set the value of operacion
     *
     * @return  self
     */ 
    public function setOperacion(?TipoOperacionPedido $operacion)
    {
        $this->operacion = $operacion;

        return $this;
    }
}