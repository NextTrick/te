<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Model\Gateway\Carrier\Ws\FedexWs;

class FedexCrossBorderCarrier extends CarrierAbstract
{      
    const ALIAS = 'FedexCrossBorder';
    
    public function __construct($serviceLocator) 
    {        
        $this->serviceLocator = $serviceLocator;      
    }
    
    public function setWs($wsConfig)
    {
        
    }
    
    public function getMultiTracking($params)
    {       
        
        return parent::getTracking($params);
    }
            
    public function isSearchKeyOwner($searchkey)
    {
        return true;
    }

}
