<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FcbApikeyApikey
 *
 * @ORM\Table(name="fcb_apikey_apikey")
 * @ORM\Entity
 */
class FcbApikeyApikey
{
    /**
     * @var integer
     *
     * @ORM\Column(name="apikeyId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $apikeyid;

    /**
     * @var string
     *
     * @ORM\Column(name="key", type="string", length=250, nullable=false)
     */
    private $key;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime", nullable=false)
     */
    private $creationdate;



    /**
     * Get apikeyid
     *
     * @return integer
     */
    public function getApikeyid()
    {
        return $this->apikeyid;
    }

    /**
     * Set key
     *
     * @param string $key
     *
     * @return FcbApikeyApikey
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set creationdate
     *
     * @param \DateTime $creationdate
     *
     * @return FcbApikeyApikey
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
