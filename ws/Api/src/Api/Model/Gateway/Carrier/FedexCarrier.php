<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Model\Gateway\Carrier\Ws\FedexWs;
use Carrier\Model\Repository\CarrierRepository;
use Api\Controller\Base\BaseResponse;

class FedexCarrier extends CarrierAbstract
{      
    const ALIAS = 'Fedex';
    const DB_ID = CarrierRepository::FEDEX_ID;
    
    public function __construct($serviceLocator) 
    {        
        $this->serviceLocator = $serviceLocator;      
    }
    
    public function setWs($wsConfig)
    {
        $this->service = new FedexWs($wsConfig);         
    }
    
    public function getTracking($params)
    {        
        $searchId = $this->saveSearch($params);  
        $this->tracking = $this->getByTrackingNumber($params['searchKey']);         
        $this->saveResquest($searchId);        
        if ($this->tracking['status']['code'] == BaseResponse::RESPONSE_STATUS_SUCCESS_CODE) {
            $lastTrack = $this->getLastTrack();
            if (empty($lastTrack)) {
                $this->updateSearch($searchId);            
            }            
        }
        
        return parent::getTracking($params);
    }
    
    public function getByTrackingNumber($trackingNumber)
    {                        
        $dateTime = date('Y-m-d H:i:s');        
        $returnData = $this->getLastValidTrack($trackingNumber);
        
        if (empty($returnData)) {
            $returnData['status']['dateTime'] = $dateTime;    
            
            $response = $this->service->getByTrackingNumber($trackingNumber);        
            if ($response['success']) {
                $responseObject = $response['data'];
                $trackingDetails = $responseObject->CompletedTrackDetails->TrackDetails;            
                if (empty($responseObject->CompletedTrackDetails->TrackDetailsCount)) {
                    $trackingDetails = array($trackingDetails);
                }

                foreach ($trackingDetails as $key => $trackingDetail) {                    
                    $returnData['trackingDetails'][$key]['trackingKey'] = $trackingDetail->TrackingNumber;

                    $statusDetail = $trackingDetail->StatusDetail;
                    $returnData['trackingDetails'][$key]['statusDetail'] = array(
                        'creationDateTime' => $statusDetail->CreationTime,
                        'code' => $statusDetail->Code,
                        'description' => $statusDetail->Description,
                        'creationDateTime' => $statusDetail->CreationTime,
                    );

                    $returnData['trackingDetails'][$key]['statusDetail']['location'] = array(                                       
                        'city' => $statusDetail->Location->City,
                        'stateOrProvinceCode' => $statusDetail->Location->StateOrProvinceCode,
                        'countryCode' => $statusDetail->Location->CountryCode,
                        'countryName' => $statusDetail->Location->CountryName,
                    );

                    if (!empty($statusDetail->Location->StreetLines)) {
                        $returnData['trackingDetails'][$key]['statusDetail']['location']['streetLines'] 
                                = $statusDetail->Location->StreetLines;
                    }

                    $returnData['trackingDetails'][$key]['carrierCode'] = $trackingDetail->CarrierCode;
                    $returnData['trackingDetails'][$key]['operatingCompanyOrCarrierDescription'] 
                            = $trackingDetail->OperatingCompanyOrCarrierDescription;

                    $returnData['trackingDetails'][$key]['destinationAddress'] = array(
                        'stateOrProvinceCode' => $trackingDetail->DestinationAddress->StateOrProvinceCode,
                        'countryCode' => $trackingDetail->DestinationAddress->CountryCode,
                        'countryName' => $trackingDetail->DestinationAddress->CountryName,
                    ); 

                    foreach ($trackingDetail->Events as $k => $event) { 
                        $this->getGMapsService()->getInfoLocation(array(
                            'search' => $event->Address->CountryName . ', ' . $event->Address->PostalCode,
                            'carrierId' => self::DB_ID,
                        ));
                        $returnData['trackingDetails'][$key]['events'][$k] = array(
                            'dateTime' => $event->Timestamp,
                            'eventCode' => $event->EventType,
                            'eventDescription' => $event->EventDescription,
                            'address' => array(
                                'postalCode' => $event->Address->PostalCode,                            
                                'countryName' => $event->Address->CountryName,
                                'countryCode' => $event->Address->CountryCode,                                
                            ),
                            'location' => array(
                                'latitud' => $this->getGMapsService()->getLatitude(),
                                'longitud' => $this->getGMapsService()->getLongitude(),
                            ),
                        );

                        if (!empty($event->Address->StateOrProvinceCode)) {
                            $returnData['trackingDetails'][$key]['events'][$k]['address']['stateOrProvinceCode'] =
                                    $event->Address->StateOrProvinceCode;
                        }
                    }

                    $firstEvent = reset($returnData['trackingDetails'][$key]['events']);
                    $returnData['trackingDetails'][$key]['originAddress'] = array(
                        'stateOrProvinceCode' => $firstEvent['address']['stateOrProvinceCode'],
                        'countryName' => $firstEvent['address']['countryName'],
                        'countryCode' => $firstEvent['address']['countryCode'],
                    );

                    $returnData['trackingDetails'][$key]['shipmentInfo'] = array(
                        'weight' => array(
                            'value' => $trackingDetail->PackageWeight->Value,
                            'units' => $trackingDetail->PackageWeight->Units,
                        ),
                        'dimensions' => array(
                            'length' => $trackingDetail->PackageDimensions->Length,
                            'width' => $trackingDetail->PackageDimensions->Width,
                            'height' => $trackingDetail->PackageDimensions->Height,
                            'units' => $trackingDetail->PackageDimensions->Units,
                        ),                
                        'notification' => array(
                            'code' => $trackingDetail->Notification->Code,
                            'Message' => $trackingDetail->Notification->Message,
                        ),                        
                        'packageSequenceNumber' => $trackingDetail->PackageSequenceNumber,                
                        'packaging' => $trackingDetail->Packaging,
                        'service' =>  array(                    
                            'description' => $trackingDetail->Service->Description,                  
                        ),                
                        'pickupDateTime' => $trackingDetail->ShipTimestamp, //shipTimestamp on fedex                                                           
                    ); 
                    if (!empty($trackingDetail->ActualDeliveryTimestamp)) {
                        $returnData['trackingDetails'][$key]['shipmentInfo']['lastUpdated'] 
                                = $trackingDetail->ActualDeliveryTimestamp; //ActualDeliveryTimestamp on fedex
                    }  
                }

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
        if (preg_match('/(\b96\d{20}\b)|(\b\d{15}\b)|(\b\d{12}\b)/', $searchkey)) {
            $return = true;
        }
        
        return $return;
    }

    /**
     * @return \Api\Model\Service\GMapsService
     */
    public function getGMapsService()
    {
        return $this->serviceLocator->get('Api\Model\GMapsService');
    }
}
