<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Model\Gateway\Carrier\Ws\DhlWs;
use Carrier\Model\Repository\CarrierRepository;
use Api\Controller\Base\BaseResponse;

class DhlCarrier extends CarrierAbstract
{
    const ALIAS = 'Dhl';
    
    const DB_ID = CarrierRepository::DHL_ID;

    const STATUS_SUCCESS = 'success';

    public function __construct($serviceLocator) 
    {
       $this->serviceLocator = $serviceLocator;       
    }
    
    public function getTracking($params = array()) 
    {
        $searchId = $this->saveSearch($params);
        $this->tracking = $this->getByTrackingNumber($params['searchKey']);
        $this->saveResquest($searchId);
        if ($this->tracking['status']['code'] == BaseResponse::RESPONSE_STATUS_SUCCESS_CODE) {
            $this->updateSearch($searchId);            
        }
        return parent::getTracking($params);
    }
    public function getByTrackingNumber($trackingNumber)
    {
        $dateTime = date('Y-m-d H:i:s');        
        $returnData = $this->getLastValidTrack($trackingNumber);
        if (empty($returnData)) {
            $returnData = $this->formatResponse(
                    $this->service->getByTrackingNumber($trackingNumber)
                );
            $returnData['status']['dateTime'] = $dateTime;    
        }
        return $returnData;
    }

    public function formatResponse($params)
    {
        $response = array();
        if($params['success']){
            $data = $params['data'];
            if($data['AWBInfo']['Status']['ActionStatus'] == self::STATUS_SUCCESS){
                $response = $this->responseOk($data);
            } else {
                $response = BaseResponse::getErrorSkeleton();
                $response['error']['message'] = $data['AWBInfo']['Status']['Condition']['ConditionData'];
                $response['error']['code'] =$data['AWBInfo']['Status']['Condition']['ConditionCode'];
                $response = array_merge(BaseResponse::getErrorSkeleton(), $response);
            }
        }
        else{
            $response = BaseResponse::getErrorSkeleton();
            $response['error']['message'] = $params['error']['message'];
            $response['error']['code'] = $params['error']['code'];
            $response = array_merge(BaseResponse::getErrorSkeleton(), $response);
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
                'code' => BaseResponse::RESPONSE_STATUS_SUCCESS_CODE,
                'dateTime' => '',
                'referenceId' => ''
            ), 
            'trackingDetails' => array(
                array(
                    'trackingKey' => $info['AWBNumber'],
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
                    'destinationAddress' => array(
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
            ),
        );
        $response = array_merge(self::getTrackingSkeleton(), $response);
        return $response;
    }
    
    
    public function isSearchKeyOwner($searchkey)
    {         
        $response = false;
        if(strlen($searchkey) != 40) {
            $response = true;
        }
        
        return $response;
        
        $return = false;
        if (preg_match('/^([0-9]{20})?([0-9]{4}[0-9]{4}[0-9]{4}[0-9]{2})$/', $searchkey)) {
            $return = true;
        }
        
        return $return;
    }
    
    public function setWs($wsConfig)
    {
        $this->service = new DhlWs(
                $wsConfig, 
                array('searchKey' => $this->searchKey)
            );         
    }
}
