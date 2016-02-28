<?php

namespace Api\Model\Gateway; 

abstract class CarrierGateway
{
     public static function getCarrierService($carrier, $config, $serviceLocator) {
        $carrier = '\Api\Model\Gateway\Carrier' . "\\" .ucwords($carrier) . 'Carrier';
        if (class_exists($carrier)) {
            return new $carrier($config, $serviceLocator);
        } else {
            throw new \Exception("Invalid gateway");
        }
    } 
} 