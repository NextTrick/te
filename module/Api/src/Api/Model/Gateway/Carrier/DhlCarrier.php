<?php

use Api\Carrier\Base\CarrierAbstract;
class DhlCarrier extends CarrierAbstract
{
    public $service;
    public function __construnct()
    {
        $this->service = new DhlService();
    }
    
    public function getTracking()
    {        
        $this->tracking = $this->http();
    }
    
    
}

