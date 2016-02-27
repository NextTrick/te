<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FcbSearchSearch
 *
 * @ORM\Table(name="fcb_search_search", indexes={@ORM\Index(name="fk_Fcb_Ts_Search_Fcb_Ts_Carrier1_idx", columns={"carrierId"}), @ORM\Index(name="fk_Fcb_Ts_Search_Fcb_Ts_ServiceApikey1_idx", columns={"serviceApikeyId"})})
 * @ORM\Entity
 */
class FcbSearchSearch
{
    /**
     * @var integer
     *
     * @ORM\Column(name="seachId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $seachid;

    /**
     * @var string
     *
     * @ORM\Column(name="tracnkingId", type="string", length=45, nullable=true)
     */
    private $tracnkingid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime", nullable=false)
     */
    private $creationdate;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=16, nullable=true)
     */
    private $ip;

    /**
     * @var \Application\Entity\FcbCarrierCarrier
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\FcbCarrierCarrier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="carrierId", referencedColumnName="carrierId")
     * })
     */
    private $carrierid;

    /**
     * @var \Application\Entity\FcbStatisticServiceApikey
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\FcbStatisticServiceApikey")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="serviceApikeyId", referencedColumnName="serviceApikeyId")
     * })
     */
    private $serviceapikeyid;



    /**
     * Get seachid
     *
     * @return integer
     */
    public function getSeachid()
    {
        return $this->seachid;
    }

    /**
     * Set tracnkingid
     *
     * @param string $tracnkingid
     *
     * @return FcbSearchSearch
     */
    public function setTracnkingid($tracnkingid)
    {
        $this->tracnkingid = $tracnkingid;

        return $this;
    }

    /**
     * Get tracnkingid
     *
     * @return string
     */
    public function getTracnkingid()
    {
        return $this->tracnkingid;
    }

    /**
     * Set creationdate
     *
     * @param \DateTime $creationdate
     *
     * @return FcbSearchSearch
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

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return FcbSearchSearch
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set carrierid
     *
     * @param \Application\Entity\FcbCarrierCarrier $carrierid
     *
     * @return FcbSearchSearch
     */
    public function setCarrierid(\Application\Entity\FcbCarrierCarrier $carrierid = null)
    {
        $this->carrierid = $carrierid;

        return $this;
    }

    /**
     * Get carrierid
     *
     * @return \Application\Entity\FcbCarrierCarrier
     */
    public function getCarrierid()
    {
        return $this->carrierid;
    }

    /**
     * Set serviceapikeyid
     *
     * @param \Application\Entity\FcbStatisticServiceApikey $serviceapikeyid
     *
     * @return FcbSearchSearch
     */
    public function setServiceapikeyid(\Application\Entity\FcbStatisticServiceApikey $serviceapikeyid = null)
    {
        $this->serviceapikeyid = $serviceapikeyid;

        return $this;
    }

    /**
     * Get serviceapikeyid
     *
     * @return \Application\Entity\FcbStatisticServiceApikey
     */
    public function getServiceapikeyid()
    {
        return $this->serviceapikeyid;
    }
}
