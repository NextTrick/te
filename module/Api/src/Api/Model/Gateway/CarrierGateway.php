<?php

namespace Api\Model\Gateway; 

abstract class CarrierGateway
{
     public static function getCarrierService($carrier) {
        $carrier = '\Api\Model\Gateway\Carrier' . "\\" .ucwords($carrier) . 'Carrier';
        if (class_exists($carrier)) {
            return new $carrier();
        } else {
            throw new \Exception("Invalid gateway");
        }
    } 
} 