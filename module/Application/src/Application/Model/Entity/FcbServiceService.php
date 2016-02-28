<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FcbServiceService
 *
 * @ORM\Table(name="fcb_service_service")
 * @ORM\Entity
 */
class FcbServiceService
{
    /**
     * @var integer
     *
     * @ORM\Column(name="serviceId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $serviceid;

    /**
     * @var string
     *
     * @ORM\Column(name="endpoint", type="string", length=256, nullable=false)
     */
    private $endpoint;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime", nullable=false)
     */
    private $creationdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="editionDate", type="datetime", nullable=true)
     */
    private $editiondate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = '1';



    /**
     * Get serviceid
     *
     * @return integer
     */
    public function getServiceid()
    {
        return $this->serviceid;
    }

    /**
     * Set endpoint
     *
     * @param string $endpoint
     *
     * @return FcbServiceService
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Get endpoint
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Set creationdate
     *
     * @param \DateTime $creationdate
     *
     * @return FcbServiceService
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
     * Set editiondate
     *
     * @param \DateTime $editiondate
     *
     * @return FcbServiceService
     */
    public function setEditiondate($editiondate)
    {
        $this->editiondate = $editiondate;

        return $this;
    }

    /**
     * Get editiondate
     *
     * @return \DateTime
     */
    public function getEditiondate()
    {
        return $this->editiondate;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return FcbServiceService
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
}
