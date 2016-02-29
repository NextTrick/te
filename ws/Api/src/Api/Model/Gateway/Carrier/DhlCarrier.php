<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Model\Gateway\Carrier\Service\DhlWs;

class DhlCarrier extends CarrierAbstract
{
    public function __construct($serviceLocator) 
    {
       $this->serviceLocator = $serviceLocator;       
    }

    public function getTracking()
    {        
        return $this->service->getTracking();
    }
    
    public function isSearchKeyOwner($searchkey)
    {
        return true;
    }
    
    public function setWs($wsConfig)
    {
        $this->service = new DhlWs($wsConfig);         
    }
}
