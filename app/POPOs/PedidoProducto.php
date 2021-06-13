<?php

namespace POPOs;

require_once __DIR__ . '/Producto.php';
require_once __DIR__ . '/Pedido.php';
require_once __DIR__ . '/PedidoEstado.php';

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
     * @ManyToOne(targetEntity="Pedido", inversedBy="productos")
     * @JoinColumn(name="pedido", referencedColumnName="codigo")
     * @Groups({"pedidos"})
     */
    private ?Pedido $pedido;

    /**
     * @ManyToOne(targetEntity="Producto")
     * @JoinColumn(name="producto", referencedColumnName="id")
     */
    private ?Producto $producto;

    /**
     * @Column(type="integer")
     */
    private int $cantidad;

    /**
     * @ManyToOne(targetEntity="PedidoEstado")
     * @JoinColumn(name="estado", referencedColumnName="id")
     */
    private ?PedidoEstado $estado;

    /**
     * @ManyToOne(targetEntity="Usuario")
     * @JoinColumn(name="responsable", referencedColumnName="id")
     */
    private ?Usuario $responsable;

    /**
     * @Column(type="datetime")
     */
    private ?\DateTimeInterface $horaInicio;

    /**
     * @Column(type="datetime")
     */
    private ?\DateTimeInterface $horaFinEstipulada;

    /**
     * @Column(type="datetime")
     */
    private ?\DateTimeInterface $horaFin;

    public function __construct(
        int $id = -1,
        ?Pedido $pedido = NULL,
        ?Producto $producto = NULL,
        int $cantidad = -1,
        ?\DateTimeInterface $horaInicio = NULL,
        ?\DateTimeInterface $horaFinEstipulada = NULL, 
        ?\DateTimeInterface $horaFin = NULL
    )
    {
        $this->id = $id;
        $this->producto = $producto;
        $this->cantidad = $cantidad;
        $this->pedido = $pedido;
        $this->horaInicio = $horaInicio;
        $this->horaFinEstipulada = $horaFinEstipulada;
        $this->horaFin = $horaFin;
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
    public function setProducto(?Producto $producto)
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
     * Get the value of estado
     */ 
    public function getEstado() : ?PedidoEstado
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */ 
    public function setEstado(?PedidoEstado $estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get the value of horaInicio
     */ 
    public function getHoraInicio() : ?\DateTimeInterface
    {
        return $this->horaInicio;
    }

    /**
     * Set the value of horaInicio
     *
     * @return  self
     */ 
    public function setHoraInicio(?\DateTimeInterface $horaInicio)
    {
        $this->horaInicio = $horaInicio;

        return $this;
    }
    
    /**
     * Get the value of horaFinEstipulada
     */ 
    public function getHoraFinEstipulada() : ?\DateTimeInterface
    {
        return $this->horaFinEstipulada;
    }

    /**
     * Set the value of horaFinEstipulada
     *
     * @return  self
     */ 
    public function setHoraFinEstipulada(?\DateTimeInterface $horaFinEstipulada)
    {
        $this->horaFinEstipulada = $horaFinEstipulada;

        return $this;
    }

    /**
     * Get the value of horaFin
     */ 
    public function getHoraFin() : ?\DateTimeInterface
    {
        return $this->horaFin;
    }

    /**
     * Set the value of horaFin
     *
     * @return  self
     */ 
    public function setHoraFin(?\DateTimeInterface $horaFin)
    {
        $this->horaFin = $horaFin;

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
    public function setResponsable(?Usuario $responsable)
    {
        $this->responsable = $responsable;

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
        $ret['estado'] = $this->estado;
        $ret['horaInicio'] = $this->horaInicio;
        $ret['horaFinEstipulada'] = $this->horaFinEstipulada;
        $ret['horaFin'] = $this->horaFin;
        return $ret;
    }
}