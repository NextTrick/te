<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Model\Gateway\Carrier\Service\DhlService;
class DhlCarrier extends CarrierAbstract
{
    public $service;
    
    public function __construnct()
    {
        $this->service = new DhlService();
    }
    
    public function getTracking()
    {        
        
        $this->tracking = $this->service->getTracking();
    }
    
    
}

