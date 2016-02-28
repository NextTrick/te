<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Service\DhlService;

class DhlCarrier
{
    public $service;
    
    public function __construct() {
       $this->service = new DhlService();
    }

    public function getTracking()
    {        
        $this->tracking = $this->service->getTracking();
    }
    
    
}

