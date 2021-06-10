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
     * @ManyToOne(targetEntity="Usuario")
     * @JoinColumn(name="empleadoTomaPedidoId", referencedColumnName="id")
     */
    private ?Usuario $empleadoTomaPedido;

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
     * Fecha-Hora de inicio de realización de pedido.
     * @Column(length=45)
     */
    private string $fechaHoraInicioPedido;

    /**
     * Fecha-Hora estipulada de finalización de pedido.
     * @Column(length=45)
     */
    private string $fechaHoraFinPedidoEstipulada;

    /**
     * Fecha-Hora en la que se terminó el pedido.
     * @column(length=45)
     */
    private string $fechaHoraFinPedido;

    //@JoinColumn(name="pedido", referencedColumnName="codigo")

    /**
     * @OneToMany(targetEntity="PedidoProducto", mappedBy="pedido")
     * @JoinColumn(name="pedido", referencedColumnName="producto")
     * @Groups({"pedidos"})
    */
    private Collection $productos;

    public function __construct(
                                string $codigo = '',
                                ?Usuario $cliente = NULL,
                                ?Usuario $empleadoTomaPedido = NULL,
                                ?Mesa $mesa = NULL,
                                string $imgPath = '',
                                string $fechaHoraInicioPedido = '',
                                string $fechaHoraFinPedidoEstipulada = '',
                                string $fechaHoraFinPedido = '',
                                Collection $productos = NULL
                                )
    {
        $this->codigo = $codigo;
        $this->cliente = $cliente;
        $this->empleadoTomaPedido = $empleadoTomaPedido;
        $this->mesa = $mesa;
        $this->imgPath = $imgPath;
        $this->fechaHoraInicioPedido = $fechaHoraInicioPedido;
        $this->fechaHoraFinPedidoEstipulada = $fechaHoraFinPedidoEstipulada;
        $this->fechaHoraFinPedido = $fechaHoraFinPedido;
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
     * Get the value of empleadoTomaPedido
     */ 
    public function getEmpleadoTomaPedido() : ?Usuario
    {
        return $this->empleadoTomaPedido;
    }

    /**
     * Set the value of empleadoTomaPedido
     *
     * @return  self
     */ 
    public function setEmpleadoTomaPedido(?Usuario $empleadoTomaPedido) : self
    {
        $this->empleadoTomaPedido = $empleadoTomaPedido;

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
     * Get the value of fechaHoraInicioPedido
     */ 
    public function getFechaHoraInicioPedido() : string
    {
        return $this->fechaHoraInicioPedido;
    }

    /**
     * Set the value of fechaHoraInicioPedido
     *
     * @return  self
     */ 
    public function setFechaHoraInicioPedido(string $fechaHoraInicioPedido) : self
    {
        $this->fechaHoraInicioPedido = $fechaHoraInicioPedido;

        return $this;
    }

    /**
     * Get the value of fechaHoraFinPedidoEstipulada
     */ 
    public function getFechaHoraFinPedidoEstipulada() : string
    {
        return $this->fechaHoraFinPedidoEstipulada;
    }

    /**
     * Set the value of fechaHoraFinPedidoEstipulada
     *
     * @return  self
     */ 
    public function setFechaHoraFinPedidoEstipulada(string $fechaHoraFinPedidoEstipulada) : self
    {
        $this->fechaHoraFinPedidoEstipulada = $fechaHoraFinPedidoEstipulada;

        return $this;
    }

    /**
     * Get the value of fechaHoraFinPedido
     */ 
    public function getFechaHoraFinPedido() : string
    {
        return $this->fechaHoraFinPedido;
    }

    /**
     * Set the value of fechaHoraFinPedido
     *
     * @return  self
     */ 
    public function setFechaHoraFinPedido(string $fechaHoraFinPedido) : self
    {
        $this->fechaHoraFinPedido = $fechaHoraFinPedido;

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
        $ret["empleado"] = $this->empleadoTomaPedido;
        $ret["mesa"] = $this->mesa;
        $ret["estado"] = $this->estado;
        $ret["fechaHoraInicioPedido"] = $this->fechaHoraInicioPedido;
        $ret["fechaHoraFinPedidoEstipulada"] = $this->fechaHoraFinPedidoEstipulada;
        $ret["fechaHoraFinPedido"] = $this->fechaHoraFinPedido;
        $ret["productos"] = $this->getProductos()->toArray();
        return $ret;
    }

}