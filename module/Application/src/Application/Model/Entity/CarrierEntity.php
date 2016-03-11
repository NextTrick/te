<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
 
/**
 * Album
 *
 * @ORM\Table(name="fcb_carrier_carrier")
 * @ORM\Entity
 */
class CarrierEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="carrierId", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="carrierid_id_seq", allocationSize=1, initialValue=1)
     */
    protected $carrierId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
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

