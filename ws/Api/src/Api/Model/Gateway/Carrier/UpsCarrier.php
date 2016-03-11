<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Model\Gateway\Carrier\Ws\UpsWs;
use Carrier\Model\Repository\CarrierRepository;
use Api\Controller\Base\BaseResponse;

class UpsCarrier extends CarrierAbstract
{      
    const ALIAS = 'Ups';
    const DB_ID = CarrierRepository::UPS_ID;
    
    public function __construct($serviceLocator) 
    {        
        $this->serviceLocator = $serviceLocator;      
    }
    
    public function setWs($wsConfig)
    {
        $this->service = new UpsWs($wsConfig);         
    }
            
    public function isSearchKeyOwner($searchkey)
    {
        $return = false;
        if (preg_match('/^1Z[A-Z0-9]{3}[A-Z0-9]{3}[0-9]{2}[0-9]{4}[0-9]{4}$/i', $searchkey)) {
            $return = true;
        }
        
        return $return;
    }
}
