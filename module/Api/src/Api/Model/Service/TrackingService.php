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
        return GatewayFactory::create($carrier, $config);
    }
}