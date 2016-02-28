<?php

use Api\Gateway\GatewayFactory;
class TrackingService
{
    public function getTracking()
    {
        $this->getCarrierTracking()->getTracking();
    }
    
    public function getCarrierTracking()
    {
        $carrier = 'dhl';
        return GatewayFactory::getCarrierService($carrier, $config);
    }
}