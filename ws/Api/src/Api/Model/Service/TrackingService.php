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
        $params = array(
            'searchKey' => '23432432',
            'key' => '432J2H2H11G11F1F1G11G1',
        );
        
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
}