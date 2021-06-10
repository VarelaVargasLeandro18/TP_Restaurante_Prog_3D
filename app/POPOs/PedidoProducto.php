<?php

namespace POPOs;

require_once __DIR__ . '/Producto.php';
require_once __DIR__ . '/Pedido.php';

/**
 * @Entity
 * @Table(name="PedidoProducto")
 */
class PedidoProducto implements \JsonSerializable{

    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    private int $id;

    /**
     * @ManyToOne(targetEntity="Producto")
     * @JoinColumn(name="producto", referencedColumnName="id")
     */
    private ?Producto $producto;

    /**
     * @ManyToOne(targetEntity="Pedido", inversedBy="productos")
     * @JoinColumn(name="pedido", referencedColumnName="codigo")
     * @Groups({"pedidos"})
     */
    private ?Pedido $pedido;

    /**
     * @Column(type="integer")
     */
    private int $cantidad;

    public function __construct(
        int $id = -1,
        ?Producto $producto = NULL,
        int $cantidad = -1,
        ?Pedido $pedido = NULL
    )
    {
        $this->id = $id;
        $this->producto = $producto;
        $this->cantidad = $cantidad;
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
     * Get the value of producto
     */ 
    public function getProducto() : ?Producto
    {
        return $this->producto;
    }

    /**
     * Set the value of producto
     *
     * @return  self
     */ 
    public function setProducto(Producto $producto)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get the value of cantidad
     */ 
    public function getCantidad() : int
    {
        return $this->cantidad;
    }

    /**
     * Set the value of cantidad
     *
     * @return  self
     */ 
    public function setCantidad(int $cantidad)
    {
        $this->cantidad = $cantidad;

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

    public function jsonSerialize()
    {
        if ( isset($this->id) )
            $ret['id'] = $this->id;
        if ( isset($this->pedido) )
            $ret['pedido'] = $this->pedido->getCodigo();
        $ret['producto'] = $this->producto;
        $ret['cantidad'] = $this->cantidad;
        return $ret;
    }
}