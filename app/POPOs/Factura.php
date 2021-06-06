<?php

namespace POPOs;

require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

use interfaces\SerializeWithJSON as SWJ;

/**
 * Representa un pago realizado por un cliente.
 * @Entity
 * @Table(name="Factura")
 */
class Factura implements SWJ {

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

    /**
     * Convierte de json a Pedido.
     */
    public static function decode ( string $serialized ) : mixed {
        
        try {
            $assoc = json_decode($serialized, true, 512, JSON_THROW_ON_ERROR);
            return self::asssocToObj($assoc);
        }
        catch ( \Exception ) {
            return NULL;
        }
        
    }

    private static function asssocToObj( array $assoc ) : ?self {
        $keysHasToHave = array_keys( (new Factura())->jsonSerialize() );
        $keysHasHave = array_keys( $assoc );
        
        if ( count( array_diff( $keysHasToHave, $keysHasHave ) ) > 0 ) return NULL;

        $id = intval($assoc["id"]);
        $cliente = intval($assoc['clienteId']);
        $fechaExp = \DateTime::createFromFormat( 'Y-mm-dd gg:ii:ss', $assoc['fechaExpedicion'] );
        $ret = new Factura(
                            $id,
                            $fechaExp,
                            new Usuario ( $cliente ),
                            $assoc['codigoPedido']
        );

        return $ret;
    }
}