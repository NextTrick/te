<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Model\Gateway\Carrier\Ws\DhlWs;

class DhlCarrier extends CarrierAbstract
{
    public $service;
    
    public $serviceLocator;

    public function __construct($serviceLocator) 
    {
       $this->serviceLocator = $serviceLocator;       
    }

    public function getTracking()
    {        
        return $this->service->getTracking();
    }
    
    public function getConfigBySearchKey($searchkey) 
    {
        parent::getConfigBySearchKey($searchkey);
    }
    
    public function setWsConfig($wsConfig)
    {
        $this->service = new DhlWs($wsConfig);
    }
}
