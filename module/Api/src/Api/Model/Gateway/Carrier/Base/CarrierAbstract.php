<?php

namespace Api\Model\Gateway\Carrier\Base; 

use Api\Model\Gateway\Carrier\Base\CarrierInterface;

abstract class CarrierAbstract implements CarrierInterface
{
    public $tracking = 'OK';
    
    public function getTracking()
    {
        return $this->tracking;
    }
    
    public function http()
    {
        
    }
    
} 