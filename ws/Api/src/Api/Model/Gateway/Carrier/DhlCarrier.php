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
    
    public function getTracking($params = array()) {
        return $this->mergeParams(
                parent::getTracking($params), 
                $this->service->getTracking($params)
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
                    'date' => $event['Date'] . ' ' . $event['Time'],                    
                    'eventCode' => $event['ServiceEvent']['EventCode'],
                    'eventDescription' => $event['ServiceEvent']['Description'],
                    'address' => array(
                        'postalCode' => '',
                        'StateOrProvinceCode' => $event['ServiceArea']['ServiceAreaCode'],
                        'countryName' => $event['ServiceArea']['Description'],
                        'countryCode' => '',                                                
                    ),      
                );
            }
        }
        $endEvent = array();
        if(!empty($events)) {
            $endEvent = end($events);
        }
        $response = array(
            'serviceHeader' => array(
                'date' => date('Y-m-d H:i:s'),
                'referenceId' => ''
            ),        
            'trackingDetails' => array(
                'trackingNumber' => $info['AWBNumber'],
                'statusDetail' => array(
                    'creationTime' => $endEvent['date'],
                    'code' => $endEvent['eventCode'],
                    'description' => $endEvent['eventDescription'],
                    'location' => array(
                        'streetLines' => '',
                        'City' => '',
                        'stateOrProvinceCode' => $endEvent['StateOrProvinceCode'],
                        'countryCode' => '',
                        'countryName' => $endEvent['countryName'],
                    ),                
                ),
                'carrierCode' => '',
                'OperatingCompanyOrCarrierDescription' => '',
                'originAddress' => array(
                    'StateOrProvinceCode' => $shipmentInfo['OriginServiceArea']['ServiceAreaCode'],
                    'countryCode' => '',
                    'countryName' => $shipmentInfo['OriginServiceArea']['Description'],
                ),
                'destionationAddress' => array(
                    'StateOrProvinceCode' => $shipmentInfo['DestinationServiceArea']['ServiceAreaCode'],
                    'countryCode' => '',
                    'countryName' => $shipmentInfo['DestinationServiceArea']['Description'],
                ),
                'events' => $events,
                'shipmentInfo' => array(
                    'weight' => array(
                        'value' => $shipmentInfo['Weight'],
                        'units' => $shipmentInfo['WeightUnit'],
                    ),
                    'dimensions' => array(
                        'length' => '',
                        'width' => '',
                        'height' => '',
                        'units' => '',
                    ),                
                    'notification' => array(
                        'code' => $shipmentInfo['Weight'],
                        'Message' => $shipmentInfo['Weight'],
                    ),                
                    'numberPieces' => $shipmentInfo['Pieces'],
                    'PackageSequenceNumber' => '',                
                    'packaging' => '',
                    'service' =>  array(                    
                        'description' => $shipmentInfo['ShipmentDesc'],                    
                    ),                
                    'pickupDate' => '', //shipTimestamp on fedex                                
                    'lastUpdated' => '', //ActualDeliveryTimestamp on fedex
                )
            ),        
        );
        return $response;
    }
    
    public function isSearchKeyOwner($searchkey)
    {
        return true;
    }
    
    public function setWs($wsConfig)
    {
        $this->service = new DhlWs(
                $wsConfig, 
                array('searchKey' => $this->searchKey)
            );         
    }
}
