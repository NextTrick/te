<?php
namespace Api\Model\Service;

use Api\Model\Gateway\CarrierGateway;

class TrackingService
{
    public $serviceLocator;
    
    public function __construct($serviceLocator) 
    {
        $this->serviceLocator = $serviceLocator;
    }
    
    public function getTracking($params)
    {
        $carrierService = $this->getCarrierService($params);
        
        return $carrierService->getTracking($params);
    }
    
    public function getMultiTracking($params)
    {
        $carrierService = $this->getCarrierService($params);
        
        return $carrierService->getTracking($params);
    }
    
    public function getCarrierService($params)
    {       
        return CarrierGateway::getCarrierService(
            $params['searchKey'], 
            $this->serviceLocator
        );
    }
    
    public function createMultitracking($params)
    {
        
    }
    
    public function updateMultitracking($params)
    {
        
    }
    
    public function deleteMultitracking($params)
    {
        
    }
}