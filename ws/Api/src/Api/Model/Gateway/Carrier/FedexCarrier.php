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
        $return = array('success' => true); 
        $tracking = array();
//        $searchId = $this->saveSearch($params);
        $response = $this->service->getByTrackingNumber($this->searchKey);  
          
        if ($response['success']) {
            $trackingDetails = $response->CompletedTrackDetails->TrackDetails;                        
            $tracking['header']['carrier'] =  $trackingDetails->CarrierCode;
            foreach ($trackingDetails as $trackingDetail) {
                $statusDetail = $trackingDetail->StatusDetail;                                
                $tracking['header']['date'] = $statusDetail->CreationTime;
                $tracking['header']['status'] = $statusDetail->Description;                       
                
                foreach ($statusDetail->events as $key => $event) {                    
                    $tracking['events'][$key]['date'] = $event->Timestamp;
                    $tracking['events'][$key]['time'] = $event->Timestamp;
                    $tracking['events'][$key]['areaCode'] = $event->Address->PostalCode;                    
                    $tracking['events'][$key]['eventCode'] = $event->EventType;
                    $tracking['events'][$key]['eventDetail'] = $event->EventDescription;
                    $tracking['events'][$key]['postalCode'] = $event->Address->PostalCode;
                    $tracking['events'][$key]['countryCode'] = $event->Address->CountryCode;
                    $tracking['events'][$key]['CountryName'] = $event->Address->CountryName;
                    $tracking['events'][$key]['StateOrProvinceCode'] = $event->Address->StateOrProvinceCode;
                }
            }
        } else {
           $return['successs'] = false;
           $return['error'] =$response['error'];
        }

        return $return;
        //        $this->updateSearch($searchId, $updateData);
    }
    
    public function isSearchKeyOwner($searchkey)
    {
        return true;
    }

}
