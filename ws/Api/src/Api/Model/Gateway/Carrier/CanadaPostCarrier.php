<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Model\Gateway\Carrier\Ws\CanadaPostWs;
use Carrier\Model\Repository\CarrierRepository;
use Api\Controller\Base\BaseResponse;

class CanadaPostCarrier extends CarrierAbstract
{
    const ALIAS = 'CanadaPost';
    
    const DB_ID = CarrierRepository::CANADAPOST_ID;

    const STATUS_SUCCESS = 'success';

    public function __construct($serviceLocator) 
    {
       $this->serviceLocator = $serviceLocator;       
    }
    
    public function getTracking($params = array()) 
    {
        $searchId = $this->saveSearch($params);
        $this->tracking = $this->getByTrackingNumber($params['searchKey'], $params);
        $this->saveResquest($searchId);
        if ($this->tracking['status']['code'] == BaseResponse::RESPONSE_STATUS_SUCCESS_CODE) {
            $this->updateSearch($searchId);            
        }
        return parent::getTracking($params);
    }
    public function getByTrackingNumber($trackingNumber, $params)
    {
        $dateTime = date('Y-m-d H:i:s');        
        $returnData = $this->getLastValidTrack($trackingNumber);
        if (empty($returnData)) {
            $returnData = $this->formatResponse(
                    $this->service->getByTrackingNumber($trackingNumber, $params)
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
            if(!empty($data['pin'])){
                $response = $this->responseOk($data);
            } else {
                $response = BaseResponse::getErrorSkeleton();
                $response['error']['message'] = $data['message']['description'];
                $response['error']['code'] = $data['message']['code'];
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
        $resp = $this->getGMapsService()->getInfoLocation('HALIFAX, NS');
        var_dump($resp);exit;
        $events = array();
        if(!empty($params['significant-events']['occurrence'])) {
            foreach ($params['significant-events']['occurrence']  as $event) {
                $events[] = array(
                    'date' => $event['2013-01-13'] . ' ' . $event['13:23:49'],                    
                    'eventCode' => $event['event-identifier'],
                    'eventDescription' => $event['event-description'],
                    'address' => array(
                        'postalCode' => '',
                        'StateOrProvinceCode' => $event['event-province'],
                        'countryName' => $event['event-site'],
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
                    'trackingKey' => $params['pin'],
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
                    'carrierCode' => self::ALIAS,
                    'OperatingCompanyOrCarrierDescription' => '',
                    'originAddress' => array(
                        'StateOrProvinceCode' => '',
                        'countryCode' => '',
                        'countryName' => '',
                    ),
                    'destinationAddress' => array(
                        'StateOrProvinceCode' => '',
                        'countryCode' => '',
                        'countryName' => '',
                    ),
                    'events' => $events,
                    'shipmentInfo' => array(
                        'weight' => array(
                            'value' => '',
                            'units' => '',
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
                        'numberPieces' => '',
                        'PackageSequenceNumber' => '',                
                        'packaging' => '',
                        'service' =>  array(                    
                            'description' => $params['service-name'],                    
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
        return true;
        $response = false;
        if(strlen($searchkey) == 16) {
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
        $this->service = new CanadaPostWs(
                $wsConfig, 
                array('searchKey' => $this->searchKey)
            );         
    }
    /**
     * @return \Api\Model\Service\GMapsService
     */
    public function getGMapsService()
    {
        return $this->serviceLocator->get('Api\Model\GMapsService');
    }
}
