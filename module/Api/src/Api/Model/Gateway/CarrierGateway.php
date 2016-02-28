<?php

namespace Api\Gateway; 

abstract class CarrierGateway
{
     public static function getCarrierService($carrier) {
        $carrier = "Carrier" . ucwords($carrier);
        if (class_exists($carrier)) {
            return new $carrier();
        } else {
            throw new \Exception("Invalid gateway");
        }
    } 
} 