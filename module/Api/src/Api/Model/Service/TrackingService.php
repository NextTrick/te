<?php
namespace Api\Model\Service;

use Api\Model\Gateway\CarrierGateway;

class TrackingService
{
    public function getTracking()
    {
        $this->getCarrierTracking()->getTracking();
    }
    
    public function getCarrierTracking()
    {
        $carrier = 'dhl';
        return CarrierGateway::getCarrierService($carrier);
    }
}