<?php
namespace Track\Model\Service;

use Util\Model\Service\Base\AbstractService;
use Track\Model\TrackRequest;
use Track\Model\Service\UbigeoService;
use Track\Model\Service\EventStatusService;
use Track\Model\Service\EventService;
use Track\Model\Service\ShipmentService;
use Api\Controller\Base\Response;
use Api\Controller\Base\BaseResponse;
use Track\Model\Request\TrackRequest;

class TrackService extends AbstractService
{        
    public function create($params)
    {
        $params = $this->getParams();        
        $trackRequest = new TrackRequest($params);
        $trackRequest->checkRequiredParams();
        $response = new Response();
        
        if (!$trackRequest->hasErrors()) {
            $trackData = $this->getTrackData($params);            
            $trackId = $this->getRepository()->save($trackData);            
            $trackcingKey = $this->generateTrackingKey($trackId);
            
            $this->getRepository()->save(
                    array('trackingKey' => $trackcingKey,
                          'trackId' => $trackId));
            
            $eventData = $this->getEventData($params);
            $eventData['trackId'] = $trackId;            
            $this->getEventService()->getRepository()->save($eventData);
            
            $shipmentData = $this->getShipmentData($params);
            $shipmentData['trackId'] = $trackId;
            $this->getShipmentService()->getRepository()->save($eventData);
            
            $response->setResponseStatusSuccess();
            $response->setResponseData($trackcingKey);
        } else {
            $errors = $trackRequest->getErrors();
            $response->setResponseStatusError();
            $response->setErrorCode(BaseResponse::ERROR_CODE_900);
            $response->setErrorMessage(BaseResponse::ERROR_MESSAGE_900);
            $response->setErrorErrors($errors);
        }   
        
        return $response->getArray();
    }
    
    protected function getTrackData($params)
    {
        $trackData = array();
        $trackData['operatingCompanyOrCarrierDescription'] = $params['operatingCompanyOrCarrierDescription'];
        $trackData['originUbigeoId'] = $this->getUbigeoService()->getRepository()
                ->getByStateOrProvinceCodeCountryCode($params['originAddress']['stateOrProvinceCode'],
                        $params['originAddress']['countryCode']);
        $trackData['detinationUbigeoId'] = $this->getUbigeoService()->getRepository()
                ->getByStateOrProvinceCodeCountryCode($params['destionationAddress']['stateOrProvinceCode'],
                        $params['originAddress']['countryCode']);
        
        return $trackData;        
    }  
    
    protected function getEventData($params)
    {
        $eventData = array();
        foreach ($params['events'] as $key => $event) {
            $key  = (int) $key;
            $eventData[$key]['dateTime'] = $event['dateTime'];
            $eventData[$key]['eventStatusId'] = $this->getEventStatusService()->getRepository()
                    ->getByCode($event['eventCode']);            
            $eventData[$key]['eventDescription'] = $event['eventDescription'];
            
            $eventData['ubigeoId'] = $this->getUbigeoService()->getRepository()
                ->getByStateOrProvinceCodeCountryCode($params['address']['stateOrProvinceCode'],
                        $params['originAddress']['countryCode']);
        }
        
        return $eventData;
    }
    
    protected function getShiptmentData($params)
    {      
        $shipment = $params['shipmentInfo'];
        
        $shipmentData = array();
        $shipmentData['weightValue'] = $shipment['weight']['value'];
        $shipmentData['weightUnits'] = $shipment['weight']['units'];
        $shipmentData['dimensionLength'] = $shipment['dimensions']['length'];
        $shipmentData['dimensionWidth'] = $shipment['dimensions']['width'];
        $shipmentData['dimensionHeight'] = $shipment['dimensions']['height'];
        $shipmentData['dimensionUnits'] = $shipment['dimensions']['units'];
        $shipmentData['pickupDateTime'] = $shipment['pickupDateTime'];
        $shipmentData['lastUpdated'] = $shipment['lastUpdated'];
        
        if (!empty($shipment['numberOfPieces'])) {
            $shipmentData['numberOfPieces'] = $shipment['numberOfPieces'];
        }
        
        if (!empty($shipment['packageSequenceNumber'])) {
            $shipmentData['packageSequenceNumber'] = $shipment['packageSequenceNumber'];
        }
        
        if (!empty($shipment['packaging'])) {
            $shipmentData['packaging'] = $shipment['packaging'];
        }
        
        if (!empty($shipment['service']['description'])) {
            $shipmentData['serviceDescription'] = $shipment['service']['description'];
        }
                
        return $shipmentData;
    }
    
    protected function generateTrackingKey($trackId)
    {
        $random = \Util\Common\String::creaeRamdonCode(5);
        $tracId = str_pad($trackId, 5, "0");
        
        return 'FCB_' . $trackId . '_' . $random;
    }
    
    /**
     * @return UbigeoService
     */
    protected function getUbigeoService()
    {
        return $this->getServiceLocator()->get('Model\UbigeoService');
    }
    
    /**
     * @return EventStatusService
     */
    protected function getEventStatusService()
    {
        return $this->getServiceLocator()->get('Model\EventStatusService');
    }
    
    /**
     * @return EventService
     */
    protected function getEventService()
    {
        return $this->getServiceLocator()->get('Model\EventService');
    }
    
    /**
     * @return ShipmentService
     */
    protected function getShipmentService()
    {
        return $this->getServiceLocator()->get('Model\ShipmentService');
    }

    public function getParams()
    {        
        return array (
            'trackingKey' => '32421231f',
            'carrierCode' => 'FCB',
            'operatingCompanyOrCarrierDescription' => 'Fedex',            
            'originAddress' => array(
                'stateOrProvinceCode' => 'EA',
                'countryCode' => 'EU',                
            ),            
            'destinationAddress' => array(
                'stateOrProvinceCode' => 'AE',
                'countryCode' => 'EU',                                
            ),
            'events' => array(
                '1' => array(
                    'dateTime' => '2016-15-29 12:09:09',                    
                    'eventCode' => 'DELIVERY',
                    'eventDescription' => 'Box was delivered',
                    'address' => array(                        
                        'stateOrProvinceCode' => 'AB',                        
                        'countryCode' => 'AU',                                                
                    ),                    
                )
            ),
            'shipmentInfo' => array(
                'weight' => array(
                    'value' => '21',
                    'units' => 'kg',
                ),
                'dimensions' => array(
                    'length' => '40',
                    'width' => '23',
                    'height' => '15',
                    'units' => 'mt',
                ),                                             
                'numberOfPieces' => '5',
                'packageSequenceNumber' => '123131',                
                'packaging' => '3234',
                'service' =>  array(                    
                    'description' => 'Fedex Premiun'                    
                ),                
                'pickupDateTime' => '2016-11-29 12:09:09', //shipTimestamp on fedex                                
                'lastUpdated' => '2016-16-29 12:09:09', //ActualDeliveryTimestamp on fedex
            )
        );      
    }    
}