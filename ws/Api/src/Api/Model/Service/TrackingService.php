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
    
    public function getTracking()
    {
        return $this->getCarrierTracking()->getTracking();
    }
    
    public function getCarrierTracking($params)
    {       
        return CarrierGateway::getCarrierService(
            $params['searchKey'], 
            $this->serviceLocator
        );
    } 
}