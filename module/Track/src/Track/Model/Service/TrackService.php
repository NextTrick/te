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
use Service\Model\Service\RequestService;
use Api\Controller\Base\BaseRestfulController;
use Track\Model\Repository\TrackRepository;

class TrackService extends AbstractService
{        
    public function create($params)
    {
        $params = $this->getParams();        
        $trackRequest = new TrackRequest($params, BaseRestfulController::METHOD_CREATE,
                $this->getServiceLocator());
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
                     
            $this->updateRequest($trackRequest->getRequestId(), $trackId);
                       
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
    
    public function update($params)
    {
        $params = $this->getParams();        
        $trackRequest = new TrackRequest($params, BaseRestfulController::METHOD_UPDATE, 
                $this->getServiceLocator());
        $trackRequest->checkRequiredParams();
        $response = new Response();
        
        if (!$trackRequest->hasErrors()) {
            $trackData = $this->getTrackData($params);            
            $trackDbData = $this->getRepository()->getByTrackingKey($params['trackingKey']);    
            if (!empty($trackDbData)) {
                $trackData['trackId'] = $trackDbData['trackId'];                        
                $this->getRepository()->save($trackData);

                $shipmentData = $this->getShipmentData($params);               
                $shipmentDbData = $this->getShipmentService()->getRepository()->getByTrackId($trackData['trackId']);
                $shipmentData['shipmentId'] = $shipmentDbData['shipmentId'];

                $this->getShipmentService()->getRepository()->save($shipmentDbData);

                $this->updateRequest($trackRequest->getRequestId(), $trackData['trackId']);
                $response->setResponseStatusSuccess();
                $response->setResponseData($params['trackingKey']);
            } else {
                $response->setResponseStatusError();
                $response->setErrorCode(BaseResponse::ERROR_CODE_900);
                $response->setErrorMessage(BaseResponse::ERROR_MESSAGE_900);
                $errors = array(
                    'code' => BaseResponse::ERROR_CODE_903,
                    'message' => BaseResponse::ERROR_MESSAGE_903,
                    'field' => 'trackingKey',
                );
                $response->setErrorErrors($errors);
            }            
        } else {
            $errors = $trackRequest->getErrors();
            $response->setResponseStatusError();
            $response->setErrorCode(BaseResponse::ERROR_CODE_900);
            $response->setErrorMessage(BaseResponse::ERROR_MESSAGE_900);
            $response->setErrorErrors($errors);
        }   
        
        return $response->getArray();
    }
    
    public function delete($params)
    {
        $params = $this->getParams();        
        $trackRequest = new TrackRequest($params, BaseRestfulController::METHOD_DELETE,
                $this->getServiceLocator());
        $trackRequest->checkRequiredParams();
        $response = new Response();
        
        if (!$trackRequest->hasErrors()) {
            $trackData = $this->getTrackData($params);            
            $trackDbData = $this->getRepository()->getByTrackingKey($params['trackingKey']);    
            if (!empty($trackDbData)) {
                $trackData['trackId'] = $trackDbData['trackId'];
                $trackData['status'] = TrackRepository::STATUS_INACTIVE;
                $this->getRepository()->save($trackData);
              
                $this->updateRequest($trackRequest->getRequestId(), $trackData['trackId']);
                $response->setResponseStatusSuccess();
                $response->setResponseData($params['trackingKey']);
            } else {
                $response->setResponseStatusError();
                $response->setErrorCode(BaseResponse::ERROR_CODE_900);
                $response->setErrorMessage(BaseResponse::ERROR_MESSAGE_900);
                $errors = array(
                    'code' => BaseResponse::ERROR_CODE_903,
                    'message' => BaseResponse::ERROR_MESSAGE_903,
                    'field' => 'trackingKey',
                );
                $response->setErrorErrors($errors);
            }
        } else {
            $errors = $trackRequest->getErrors();
            $response->setResponseStatusError();
            $response->setErrorCode(BaseResponse::ERROR_CODE_900);
            $response->setErrorMessage(BaseResponse::ERROR_MESSAGE_900);
            $response->setErrorErrors($errors);
        }   
        
        return $response->getArray();
    }
    
    protected function updateRequest($requestId, $trackId)
    {
        $data = array(
            'trackId' => $trackId,
            'requestId' => $requestId,
        );
        
        $this->getRequestService()->getRepository()->save($data);
    }
    
    protected function getTrackData($params)
    {
        $trackData = array();
        
        if (!empty($params['trackingKey'])) {
            $trackData['trackingKey'] = $params['trackingKey'];
        }
        
        if (!empty($params['operatingCompanyOrCarrierDescription'])) {
            $trackData['operatingCompanyOrCarrierDescription'] = $params['operatingCompanyOrCarrierDescription'];
        }
        
        if (!empty($params['originAddress']['stateOrProvinceCode']) && !empty($params['originAddress']['countryCode'])) {
            $originUbigeo = $this->getUbigeoService()->getRepository()
                ->getByStateOrProvinceCodeCountryCode($params['originAddress']['stateOrProvinceCode'],
                        $params['originAddress']['countryCode']);
            
            $trackData['originUbigeoId'] = $originUbigeo['ubigeoId'];
        }
        
        if (!empty($params['destionationAddress']['stateOrProvinceCode']) && !empty($params['destionationAddress']['countryCode'])) {
            $detinationUbigeo = $this->getUbigeoService()->getRepository()
                ->getByStateOrProvinceCodeCountryCode($params['destionationAddress']['stateOrProvinceCode'],
                        $params['destionationAddress']['countryCode']);
            
            $trackData['detinationUbigeoId'] = $detinationUbigeo['ubigeoId'];
        }
                                
        return $trackData;        
    }  
    
    protected function getEventData($params)
    {
        $eventData = array();
        foreach ($params['events'] as $key => $event) {
            $key  = (int) $key;
            $eventData[$key]['dateTime'] = $event['dateTime'];
            $eventStatus = $this->getEventStatusService()->getRepository()
                    ->getByCode($event['eventCode']);            
            $eventData[$key]['eventStatusId'] = $eventStatus['eventStatusId']; 
            
            $eventData[$key]['eventDescription'] = $event['eventDescription'];

            $ubigeo = $this->getUbigeoService()->getRepository()
                ->getByStateOrProvinceCodeCountryCode($params['address']['stateOrProvinceCode'],
                        $params['originAddress']['countryCode']);
            $eventData['ubigeoId'] = $ubigeo['ubigeoId'];
        }
        
        return $eventData;
    }
    
    protected function getShipmentData($params)
    {      
        $shipment = array();
        $shipmentData = array();
        
        if (!empty($params['shipmentInfo'])) {
            $shipment = $params['shipmentInfo'];
        }                        

        if (!empty($shipment['weight']['value'])) {
            $shipmentData['weightValue'] = $shipment['weight']['value'];
        }
        
        if (!empty($shipment['weight']['units'])) {
            $shipmentData['weightUnits'] = $shipment['weight']['units'];
        }
        
        if (!empty($shipment['dimensions']['length'])) {
            $shipmentData['dimensionLength'] = $shipment['dimensions']['length'];
        }
        
        if (!empty($shipment['dimensions']['width'])) {
            $shipmentData['dimensionWidth'] = $shipment['dimensions']['width'];
        }
        
        if (!empty($shipment['dimensions']['height'])) {
            $shipmentData['dimensionHeight'] = $shipment['dimensions']['height'];
        }
        
        if (!empty($shipment['dimensions']['units'])) {
            $shipmentData['dimensionUnits'] = $shipment['dimensions']['units'];
        }
        
        if (!empty($shipment['pickupDateTime'])) {
            $shipmentData['pickupDateTime'] = $shipment['pickupDateTime'];
        }
        
        if (!empty($shipment['lastUpdated'])) {
            $shipmentData['lastUpdated'] = $shipment['lastUpdated'];
        }
        
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
    
    /**
     * @return RequestService
     */
    protected function getRequestService()
    {
        return $this->getServiceLocator()->get('Model\ServiceRequestService');
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
    
    public function getUpdateParams()
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
    
    public function getDeleteParams()
    {        
        return array (
            'trackingKey' => '32421231f',                        
        );      
    }
}