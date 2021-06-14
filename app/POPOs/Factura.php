<?php

namespace POPOs;

require_once __DIR__ . '/Usuario.php';

/**
 * Representa un pago realizado por un cliente.
 * @Entity
 * @Table(name="Factura")
 */
class Factura implements \JsonSerializable {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @Column(type="datetime")
     */
    private ?\DateTimeInterface $fechaExpedicion;

    /**
     * @ManyToOne(targetEntity="Usuario")
     * @JoinColumn(name="clienteId", referencedColumnName="id")
     */
    private ?Usuario $cliente;

    /**
     * @ManyToOne(targetEntity="Pedido")
     * @JoinColumn(name="codigoPedido", referencedColumnName="codigo")
     */
    private ?Pedido $pedido;

    public function __construct(
                                int $id = -1,
                                ?\DateTime $fechaExpedicion = NULL,
                                ?Usuario $cliente = NULL,
                                ?Pedido $pedido = NULL
    )
    {
        $this->id = $id;
        $this->fechaExpedicion = $fechaExpedicion;
        $this->cliente = $cliente;
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
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of fechaExpedicion
     */ 
    public function getFechaExpedicion() : \DateTimeInterface
    {
        return $this->fechaExpedicion;
    }

    /**
     * Set the value of fechaExpedicion
     *
     * @return  self
     */ 
    public function setFechaExpedicion(\DateTimeInterface $fechaExpedicion)
    {
        $this->fechaExpedicion = $fechaExpedicion;

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
    public function setCliente(?Usuario $cliente)
    {
        $this->cliente = $cliente;

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
        $ret["fechaExpedicion"] = $this->fechaExpedicion;
        $ret["cliente"] = $this->cliente;
        $ret["codigoPedido"] = $this->codigoPedido;
        return $ret;
    }
}