<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Model\Gateway\Carrier\Ws\FedexWs;

class FedexCarrier extends CarrierAbstract
{      
    const ALIAS = 'Fedex';
    
    public function __construct($serviceLocator) 
    {        
        $this->serviceLocator = $serviceLocator;      
    }
    
    public function setWs($wsConfig)
    {
        $this->service = new FedexWs($wsConfig);         
    }
    
    public function getTracking($params)
    {        
        $searchId = $this->saveSearch($params);        
        $response = $this->service->getByTrackingNumber($this->searchKey);         
        $this->updateSearch($searchId, $updateData);
    }
    
    public function isSearchKeyOwner($searchkey)
    {
        return true;
    }

}
