<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Model\Gateway\Carrier\Ws\UspsWs;
use Carrier\Model\Repository\CarrierRepository;
use Api\Controller\Base\BaseResponse;

class UspsCarrier extends CarrierAbstract
{      
    const ALIAS = 'Usps';
    const DB_ID = CarrierRepository::USPS_ID;
        
    public function setWs($wsConfig)
    {
        $this->service = new UspsWs($wsConfig);         
    }
    
    public function getTracking($params)
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
            $returnData = array();
            
            $response = $this->service->getByTrackId($trackingNumber);             
            if ($response['success']) {
                $responseData = $response['data'];                   
                $trackingDetails = array();
                
                $trackingInfos = $responseData['TrackResponse'];
                if (!empty($trackingInfos['TrackInfo'])) {
                    $trackingInfos = array($trackingInfos['TrackInfo']);
                }
                
                foreach ($trackingInfos as $trackingInfo) {
                     $statusDetail = array (
                        'creationDateTime' => $trackingInfo['TrackSummary']['EventDate'],
                        'description' => $trackingInfo['TrackSummary']['Event'],
                        'location' => array(                            
                            'City' => $trackingInfo['TrackSummary']['EventCity'],
                            'stateOrProvinceCode' => $trackingInfo['TrackSummary']['EventState'], 
                            'postalCode' => $trackingInfo['TrackSummary']['EventZIPCode'],
                        ), 
                    );
                    
                    if (!empty($trackingInfo['TrackSummary']['EventCountry'])) {
                        $statusDetail['location']['countryName'] = $trackingInfo['TrackSummary']['EventCountry'];
                    }
                    
                    $originAddress = array(
                        'stateOrProvinceCode' => $trackingInfo['OriginState'],
                        'postalCode' => $trackingInfo['OriginZip'],
                    );
                    
                    $destinationAddress = array(
                        'stateOrProvinceCode' => $trackingInfo['DestinationState'],
                        'postalCode' => $trackingInfo['DestinationZip'],
                    );
                    
                    $trackingDetailsResponse = $trackingInfo['TrackDetail'];
                    if (!empty($trackingDetailsResponse['EventTime'])) {
                        $trackingDetailsResponse = array($trackingInfo['TrackDetail']);
                    }
                    
                    $events = array();
                    foreach ($trackingDetailsResponse as $key => $trackingDetail) {
                        $events[$key] = array(
                            'dateTime' => $trackingDetail['EventDate'],                    
                            'eventCode' => $trackingDetail['EventCode'],
                            'eventDescription' => $trackingDetail['Event'],
                            'address' => array(
                                'postalCode' => $trackingDetail['EventZIPCode'],
                                'stateOrProvinceCode' => $trackingDetail['EventState'],                                             
                            ),
                        );
                        
                        if (!empty($trackingDetail['EventCountry'])) {
                            $events[$key]['address']['countryName'] =  $trackingDetail['EventCountry'];
                        }
                    }
                                  
                    $firstEvent = $events[$key];
                    $lastEvent = $events[0];
                    
                    $shipmentInfo = array(                            
                        'service' =>  array(                    
                            'description' => $trackingInfo['Service']                    
                        ),                
                        'pickupDateTime' => $firstEvent['dateTime'],
                        'lastUpdated' => $lastEvent['dateTime'],
                    );   
                    
                    $trackingDetails[] = array(                    
                        'trackingKey' => $trackingNumber,
                        'statusDetail' => $statusDetail,
                        'operatingCompanyOrCarrierDescription' => $trackingInfo['Service'],
                        'originAddress' => $originAddress,
                        'destinationAddress' => $destinationAddress,
                        'events' => $events,
                        'shipmentInfo' => $shipmentInfo,                        
                    );
                }
                
                $returnData['trackingDetails'] = $trackingDetails;
                $returnData['status']['dateTime'] = $dateTime;   
                $returnData['status']['code'] = BaseResponse::RESPONSE_STATUS_SUCCESS_CODE;            
                $returnData = array_merge(self::getTrackingSkeleton(), $returnData);                       
            } else {                
                $returnData['status']['code'] = BaseResponse::RESPONSE_STATUS_ERROR_CODE;
                $returnData['error']['code'] = BaseResponse::STATUS_CODE_500;
                $returnData['error']['message'] = self::ERROR_GENERIC_MESSAGE;

                $returnData = array_merge(BaseResponse::getErrorSkeleton(), $returnData);
            }
        } else {
            $returnData['status']['dateTime'] = $dateTime;
        }
        
        return $returnData;
    }
            
    public function isSearchKeyOwner($searchkey)
    {        
        $return = false;
        if (preg_match('/^([0-9]{20})?([0-9]{4}[0-9]{4}[0-9]{4}[0-9]{2})$/', $searchkey)) {
            $return = true;
        }
        
        return $return;
    }
}
