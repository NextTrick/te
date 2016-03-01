<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Model\Gateway\Carrier\Ws\DhlWs;

class DhlCarrier extends CarrierAbstract
{
    const ALIAS = 'Dhl';
    
    public function __construct($serviceLocator) 
    {
       $this->serviceLocator = $serviceLocator;       
    }
    
    public function getTracking() {
        
        return $this->mergeParams(
                parent::getTracking(), 
                $this->service->getTracking()
            );
    }
    private function mergeParams($params, $paramsCarrier)
    {
        $info = reset($paramsCarrier['AWBInfo']);
        $shipmentInfo = $info['ShipmentInfo'];
        $events = array();
        if(!empty($info['ShipmentInfo']['ShipmentEvent'])) {
            foreach ($info['ShipmentInfo']['ShipmentEvent']  as $event) {
                $events[] = array(
                    'date' => $event['Date'],
                    'time' => $event['Time'],
                    'eventCode' => $event['ServiceEvent']['EventCode'],
                    'eventDetail' => $event['ServiceEvent']['Description'],
                    'areaCode' => $event['ServiceArea']['ServiceAreaCode'],
                    'areaDetail' => $event['ServiceArea']['Description'],
                );
            }
        }
        $response = array(
            'header' => array(
                'carrier' => self::ALIAS,
                'date' => '1/4/2014 1:22 AM ',
                'from' => $shipmentInfo['OriginServiceArea']['ServiceAreaCode'] . ', '. 
                            $shipmentInfo['OriginServiceArea']['Description'],
                'to' => $shipmentInfo['DestinationServiceArea']['ServiceAreaCode'] . ', '. 
                            $shipmentInfo['DestinationServiceArea']['Description'],),
            'events' => $events,
            'shipmentInfo' => array(
                'tracking' => $info['AWBNumber'],
                'notification' => '',
                'numberPieces' => '',
                'packageNumber' => '',
                'packaging' => '',
                'pickupDate' => '',
                'service' => '',
                'statusMessage' => '',
                'weight' => '',
                'lastUpdated' => '',
            )
        );
        return $response;
    }
    public function getTsracking()
    {        
        return $this->service->getTracking();
    }
    
    public function isSearchKeyOwner($searchkey)
    {
        return false;
    }
    
    public function setWs($wsConfig)
    {
        $this->service = new DhlWs(
                $wsConfig, 
                array('searchKey' => $this->searchKey)
            );         
    }
}
