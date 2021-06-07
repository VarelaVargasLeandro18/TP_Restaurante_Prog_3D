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
     */
    private int $id;

    /**
     * @Column(type="datetime")
     */
    private ?\DateTime $fechaExpedicion;

    /**
     * @ManyToOne(targetEntity="Usuario")
     * @JoinColumn(name="clienteId", referencedColumnName="id")
     */
    private ?Usuario $cliente;

    /**
     * @Column(length=5)
     */
    private string $codigoPedido;

    public function __construct(
                                int $id = -1,
                                ?\DateTime $fechaExpedicion = NULL,
                                ?Usuario $cliente = NULL,
                                string $codigoPedido = ''
    )
    {
        $this->id = $id;
        $this->fechaExpedicion = $fechaExpedicion;
        $this->cliente = $cliente;
        $this->codigoPedido = $codigoPedido;
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

    public function jsonSerialize() : mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["fechaExpedicion"] = $this->fechaExpedicion;
        $ret["cliente"] = $this->cliente;
        $ret["codigoPedido"] = $this->codigoPedido;
        return $ret;
    }
}