<?php

namespace POPOs;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/Producto.php';
require_once __DIR__ . '/Mesa.php';
require_once __DIR__ . '/PedidoEstado.php';
require_once __DIR__ . '/PedidoProducto.php';

/**
 * Representa un pedido realizado por un cliente
 * @Entity
 * @Table(name="Pedido")
 */
class Pedido implements \JsonSerializable
{

    /**
     * @Id
     * @Column(length=5)
     */
    private string $codigo;

    /**
     * @ManyToOne(targetEntity="Usuario")
     * @JoinColumn(name="clienteId", referencedColumnName="id")
     */
    private ?Usuario $cliente;

    /**
     * @ManyToOne(targetEntity="Mesa")
     * @JoinColumn(name="mesaId", referencedColumnName="id")
     */
    private ?Mesa $mesa;

    /**
     * @Column(length=255)
     */
    private string $imgPath;

    /**
     * @OneToMany(targetEntity="PedidoProducto", mappedBy="pedido")
     * @JoinColumn(name="pedido", referencedColumnName="producto")
     * @Groups({"pedidos"})
    */
    private Collection $productos;

    public function __construct(
                                string $codigo = '',
                                ?Usuario $cliente = NULL,
                                ?Mesa $mesa = NULL,
                                string $imgPath = '',
                                Collection $productos = NULL
                                )
    {
        $this->codigo = $codigo;
        $this->cliente = $cliente;
        $this->mesa = $mesa;
        $this->imgPath = $imgPath;
        
        if ( $productos === NULL ) $this->productos = new ArrayCollection();
        else $this->productos = $productos;
    }   

    /**
     * Get the value of codigo
     */ 
    public function getCodigo() : string
    {
        return $this->codigo;
    }

    /**
     * Set the value of codigo
     *
     * @return  self
     */ 
    public function setCodigo(string $codigo) : self
    {
        $this->codigo = $codigo;

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
     * Get the value of mesa
     */ 
    public function getMesa() : ?Mesa
    {
        return $this->mesa;
    }

    /**
     * Set the value of mesa
     *
     * @return  self
     */ 
    public function setMesa(?Mesa $mesa) : self
    {
        $this->mesa = $mesa;

        return $this;
    }

    /**
     * Get the value of imgPath
     */ 
    public function getImgPath() : string
    {
        return $this->imgPath;
    }

    /**
     * Set the value of imgPath
     *
     * @return  self
     */ 
    public function setImgPath(string $imgPath) : self
    {
        $this->imgPath = $imgPath;

        return $this;
    }
    
    /**
     * Get the value of productos
     */ 
    public function getProductos() : Collection
    {
        return $this->productos;
    }

    /**
     * Set the value of productos
     *
     * @return  self
     */ 
    public function setProductos(Collection $productos)
    {
        $this->productos = $productos;

        return $this;
    }

    /**
     * Add a producto
     * 
     * @return self
     */
    public function addProducto(PedidoProducto $producto) : self {
        $this->productos->add($producto);
        $producto->setPedido($this);
        return $this;
    }

    /**
     * Removes a producto
     * 
     * @return self
     */
    public function removeProducto(PedidoProducto $producto) : self {
        $this->productos->remove($producto);
        $producto->setPedido(NULL);
        return $this;
    }

    public function jsonSerialize() : mixed
    {
        $ret = array();
        if ( isset($this->codigo) )
            $ret["codigo"] = $this->codigo;
        $ret["cliente"] = $this->cliente;
        $ret["mesa"] = $this->mesa;
        $ret["productos"] = $this->getProductos()->toArray();
        return $ret;
    }

}