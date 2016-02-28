<?php

namespace Api\Gateway; 

abstract class GatewayFactory
{
     public static function create($carrier) {
        $carrier = "Carrier" . ucwords($carrier);
        if (class_exists($carrier)) {
            return new $carrier();
        }
        else {
            throw new \Exception("Invalid gateway");
        }
    } 
} 