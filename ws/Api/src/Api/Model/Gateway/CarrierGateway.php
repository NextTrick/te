<?php

namespace Api\Model\Gateway; 

class CarrierGateway
{
    public static function getCarrierService($searchKey, $serviceLocator) 
    {               
        $appCarrierService = $this->getAppCarrierService($serviceLocator);
        $carriersData = $appCarrierService->getRepository()->getByStatus();
        
        foreach ($carriersData as $carrierData) {
            $carrierObjectPath = '\Api\Model\Gateway\Carrier' . "\\" . ucwords($carrierData['alias']) . 'Carrier';
            $carrier = new $carrierObjectPath($serviceLocator);
            if (!$carrier->getConfigBySearchKey($searchKey)) {
                $config = $serviceLocator->get('config');                
                $carrier->setWsConfig($config['carrier'][$carrier['alias']]['tracking']);
                break;                
            }
        }
       
        if (is_object($carrier)) {
            return new $carrier;
        } else {
            throw new \Exception("Invalid gateway");
        }
    }
    
    public function getAppCarrierService($serviceLocator)
    {
        return $serviceLocator->get('Model\CarrierService');
    }
} 