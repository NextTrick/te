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
        $response = false;
        return $response;
    }
}
