<?php

namespace Api\Model\Gateway\Carrier\Base; 

abstract class CarrierAbstract implements GatewayInterface
{
    public $tracking;
    
    public function getTracking()
    {
        return $this->tracking;
    }
    
    public function http()
    {
        
    }
    
} 