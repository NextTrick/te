<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Model\Gateway\Carrier\Ws\FedexWs;

class FedexCarrier extends CarrierAbstract
{      
    const ALIAS = 'Fedex';
    
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
        $this->getByTrackingNumber($params);
        
        return parent::getTracking($params);
    }
    
    public function getByTrackingNumber($params)
    {        
        $returnData = array();
        $dateTime = date('Y-m-d H:i:s');   
        $returnData['status']['dateTime'] = $dateTime;
        
//        $searchId = $this->saveSearch($params);        
        $response = $this->service->getByTrackingNumber($this->searchKey);        
        if ($response['success']) {  
            $responseObject = $response['data'];
            $trackingDetails = $responseObject->CompletedTrackDetails->TrackDetails;            
            if (empty($responseObject->CompletedTrackDetails->TrackDetailsCount)) {
                $trackingDetails = array($trackingDetails);
            }
            
            foreach ($trackingDetails as $trackingDetail) {                    
                $returnData['trackingDetails']['trackingNumber'] = $trackingDetail->TrackingNumber;
                
                $statusDetail = $trackingDetail->StatusDetail;
                $returnData['trackingDetails']['statusDetail'] = array(
                    'creationDateTime' => $statusDetail->CreationTime,
                    'code' => $statusDetail->Code,
                    'description' => $statusDetail->Description,
                    'creationDateTime' => $statusDetail->CreationTime,
                );

                $returnData['trackingDetails']['statusDetail']['location'] = array(                                       
                    'city' => $statusDetail->Location->City,
                    'stateOrProvinceCode' => $statusDetail->Location->StateOrProvinceCode,
                    'countryCode' => $statusDetail->Location->CountryCode,
                    'countryName' => $statusDetail->Location->CountryName,
                );
                
                if (!empty($statusDetail->Location->StreetLines)) {
                    $returnData['trackingDetails']['statusDetail']['location']['streetLines'] 
                            = $statusDetail->Location->StreetLines;
                }
                                
                $returnData['trackingDetails']['carrierCode'] = $trackingDetail->CarrierCode;
                $returnData['trackingDetails']['OperatingCompanyOrCarrierDescription'] = $trackingDetail->OperatingCompanyOrCarrierDescription;

                $returnData['trackingDetails']['destionationAddress'] = array(
                    'stateOrProvinceCode' => $trackingDetail->DestinationAddress->StateOrProvinceCode,
                    'countryCode' => $trackingDetail->DestinationAddress->CountryCode,
                    'countryName' => $trackingDetail->DestinationAddress->CountryName,
                ); 
 
                foreach ($trackingDetail->Events as $key => $event) {                    
                    $returnData['trackingDetails']['events'][$key] = array(
                        'dateTime' => $event->Timestamp,
                        'eventCode' => $event->EventType,
                        'eventDescription' => $event->EventDescription,
                        'address' => array(
                            'postalCode' => $event->Address->PostalCode,                            
                            'countryName' => $event->Address->CountryName,
                            'countryCode' => $event->Address->CountryCode,                                
                        ),
                    );
                    
                    if (!empty($event->Address->StateOrProvinceCode)) {
                        $returnData['trackingDetails']['events'][$key]['address']['stateOrProvinceCode'] =
                                $event->Address->StateOrProvinceCode;
                    }
                }
                
                $firstEvent = reset($returnData['trackingDetails']['events']);
                $returnData['trackingDetails']['originAddress'] = array(
                    'stateOrProvinceCode' => $firstEvent['address']['stateOrProvinceCode'],
                    'countryName' => $firstEvent['address']['countryName'],
                    'countryCode' => $firstEvent['address']['countryCode'],
                );
                
                $returnData['trackingDetails']['shipmentInfo'] = array(
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
                    'NumberOfPieces' => '',
                    'packageSequenceNumber' => $trackingDetail->PackageSequenceNumber,                
                    'packaging' => $trackingDetail->Packaging,
                    'service' =>  array(                    
                        'description' => $trackingDetail->Service->Description,                  
                    ),                
                    'pickupDateTime' => $trackingDetail->ShipTimestamp, //shipTimestamp on fedex                                                           
                ); 
                if (!empty($trackingDetail->ActualDeliveryTimestamp)) {
                    $returnData['trackingDetails']['shipmentInfo']['lastUpdated'] 
                            = $trackingDetail->ActualDeliveryTimestamp; //ActualDeliveryTimestamp on fedex
                }  
            }
                                    
            $returnData['status']['code'] = self::RESPONSE_STATUS_SUCCESS_CODE;            
            $returnData = array_merge($this->trackingSkeleton, $returnData);
        } else {                
            $returnData['status']['code'] = self::RESPONSE_STATUS_ERROR_CODE_CODE;
            $returnData['error']['code'] = self::ERROR_GENERIC_CODE;
            $returnData['error']['message'] = self::ERROR_GENERIC_MESSAGE;

            $returnData = array_merge_recursive($this->errorSkeleton, $returnData);
        }
        
        return $returnData;
        //        $this->updateSearch($searchId, $updateData);
    }
    
    public function isSearchKeyOwner($searchkey)
    {
        return true;
    }

}
