<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FcbCarrierCarrier
 *
 * @ORM\Table(name="fcb_carrier_carrier")
 * @ORM\Entity
 */
class FcbCarrierCarrier
{
    /**
     * @var integer
     *
     * @ORM\Column(name="carrierId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $carrierid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime", nullable=false)
     */
    private $creationdate;



    /**
     * Get carrierid
     *
     * @return integer
     */
    public function getCarrierid()
    {
        return $this->carrierid;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return FcbCarrierCarrier
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return FcbCarrierCarrier
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set creationdate
     *
     * @param \DateTime $creationdate
     *
     * @return FcbCarrierCarrier
     */
    public function setCreationdate($creationdate)
    {
        $this->creationdate = $creationdate;

        return $this;
    }

    /**
     * Get creationdate
     *
     * @return \DateTime
     */
    public function getCreationdate()
    {
        return $this->creationdate;
    }
}
