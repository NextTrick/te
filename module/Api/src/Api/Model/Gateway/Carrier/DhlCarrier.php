<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Model\Gateway\Carrier\Service\DhlService;

class DhlCarrier extends CarrierAbstract
{
    public $service;
    
    public $serviceLocator;


    public function __construct($config, $serviceLocator) {
       $this->serviceLocator = $serviceLocator;
       $this->service = new DhlService($config);
    }

    public function getTracking()
    {        
        return $this->service->getTracking();
    }
    
    
}

