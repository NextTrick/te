<?php

namespace Application\Entity;
/**
 * @Entity @Table(name="fcb_carrier_carrier")
 */
class CarrierEntity
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     */
    protected $carrierId;
    /**
     * @Column(type="string")
     * @var string
     */
    protected $name;

    public function getCarrierId()
    {
        return $this->carrierId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}

