<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Model\Gateway\Carrier\Ws\DhlWs;

class DhlCarrier extends CarrierAbstract
{
    const ALIAS = 'Dhl';
    
    const STATUS_SUCCESS = 'success';


    public function __construct($serviceLocator) 
    {
       $this->serviceLocator = $serviceLocator;       
    }
    
    public function getTracking($params = array()) {
        return $this->formatResponse($this->service->getTracking($params));
    }
    
    public function formatResponse($params)
    {
        $response = array();
        if($params['success']){
            $data = $params['data'];
            if($data['AWBInfo']['Status']['ActionStatus'] == self::STATUS_SUCCESS){
                $response = $this->responseOk($data);
            } else {
                $response = $this->errorSkeleton;
                $response['error']['message'] = $data['AWBInfo']['Status']['Condition']['ConditionData'];
                $response['error']['code'] =$data['AWBInfo']['Status']['Condition']['ConditionCode'];
            }
        }
        else{
            $response = $this->errorSkeleton;
            $response['error']['message'] = $params['error']['message'];
            $response['error']['code'] = $params['error']['code'];
        }
        return $response;
    }

    public function responseOk($params)
    {
        $info = $params['AWBInfo'];
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
            'status' => array(
                'code' => self::RESPONSE_STATUS_SUCCESS_CODE,
                'dateTime' => '',
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
                        'stateOrProvinceCode' => $endEvent['address']['StateOrProvinceCode'],
                        'countryCode' => '',
                        'countryName' => $endEvent['address']['countryName'],
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
                        'code' => '',
                        'Message' => '',
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
