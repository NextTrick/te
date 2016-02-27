<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FcbStatisticServiceApikey
 *
 * @ORM\Table(name="fcb_statistic_service_apikey", indexes={@ORM\Index(name="fk_Fcb_Ts_EndPointApikey_FcbTs_Ts_ApiKey_idx", columns={"apikeyId"}), @ORM\Index(name="fk_Fcb_Ts_ServiceApikey_Fcb_Ts_Service1_idx", columns={"serviceId"})})
 * @ORM\Entity
 */
class FcbStatisticServiceApikey
{
    /**
     * @var integer
     *
     * @ORM\Column(name="serviceApikeyId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $serviceapikeyid;

    /**
     * @var integer
     *
     * @ORM\Column(name="counter", type="integer", nullable=false)
     */
    private $counter = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime", nullable=false)
     */
    private $creationdate;

    /**
     * @var \Application\Entity\FcbApikeyApikey
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\FcbApikeyApikey")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="apikeyId", referencedColumnName="apikeyId")
     * })
     */
    private $apikeyid;

    /**
     * @var \Application\Entity\FcbServiceService
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\FcbServiceService")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="serviceId", referencedColumnName="serviceId")
     * })
     */
    private $serviceid;



    /**
     * Get serviceapikeyid
     *
     * @return integer
     */
    public function getServiceapikeyid()
    {
        return $this->serviceapikeyid;
    }

    /**
     * Set counter
     *
     * @param integer $counter
     *
     * @return FcbStatisticServiceApikey
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;

        return $this;
    }

    /**
     * Get counter
     *
     * @return integer
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * Set creationdate
     *
     * @param \DateTime $creationdate
     *
     * @return FcbStatisticServiceApikey
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
     * Set apikeyid
     *
     * @param \Application\Entity\FcbApikeyApikey $apikeyid
     *
     * @return FcbStatisticServiceApikey
     */
    public function setApikeyid(\Application\Entity\FcbApikeyApikey $apikeyid = null)
    {
        $this->apikeyid = $apikeyid;

        return $this;
    }

    /**
     * Get apikeyid
     *
     * @return \Application\Entity\FcbApikeyApikey
     */
    public function getApikeyid()
    {
        return $this->apikeyid;
    }

    /**
     * Set serviceid
     *
     * @param \Application\Entity\FcbServiceService $serviceid
     *
     * @return FcbStatisticServiceApikey
     */
    public function setServiceid(\Application\Entity\FcbServiceService $serviceid = null)
    {
        $this->serviceid = $serviceid;

        return $this;
    }

    /**
     * Get serviceid
     *
     * @return \Application\Entity\FcbServiceService
     */
    public function getServiceid()
    {
        return $this->serviceid;
    }
}
