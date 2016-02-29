<?php

namespace Api\Model\Gateway; 

use Carrier\Model\Service\CarrierService;

class CarrierGateway
{
    public static function getCarrierService($searchKey, $serviceLocator) 
    {        
        $appCarrierService = self::getAppCarrierService($serviceLocator);
        $carriersData = $appCarrierService->getRepository()->getByStatus();
               
        foreach ($carriersData as $carrierData) {            
            $carrierObjectPath = '\Api\Model\Gateway\Carrier' . "\\" . ucwords($carrierData['alias']) . 'Carrier';
            $carrier = new $carrierObjectPath($serviceLocator);
            if ($carrier->checkSearchKeyOwner($searchKey)) {                
                break;                
            }
        }
               
        if (is_object($carrier)) {
            return $carrier;
        } else {
            throw new \Exception("Invalid gateway");
        }
    }
    
    /**    
     * @param type $serviceLocator
     * @return CarrierService
     */
    public function getAppCarrierService($serviceLocator)
    {
        return $serviceLocator->get('Model\CarrierService');
    }
} 