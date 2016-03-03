<?php

namespace Api\Model\Gateway\Carrier\Base; 

use Api\Model\Gateway\Carrier\Base\CarrierInterface;
use Search\Model\Service\SearchService;
use Statistic\Model\Service\ServiceApikeyService;
use Apikey\Model\Service\ApikeyService;
use Search\Model\Service\TrackService;

abstract class CarrierAbstract implements CarrierInterface
{
    const RESPONSE_STATUS_SUCCESS_CODE = 'SUCCESS';    
    const RESPONSE_STATUS_ERROR_CODE = 'ERROR';
    
    const ERROR_GENERIC_CODE = 500;
    const ERROR_GENERIC_MESSAGE = 'Failed Ws connection';
    
    public $trackingSkeleton = array(
        'status' => array(
            'code' => self::RESPONSE_STATUS_SUCCESS_CODE,
            'dateTime' => '',
            'referenceId' => ''
        ),        
        'trackingDetails' => array(
            array(
                'trackingKey' => '',
                'statusDetail' => array(
                    'creationDateTime' => '',
                    'code' => '',
                    'description' => '',
                    'location' => array(
                        'streetLines' => '',
                        'City' => '',
                        'stateOrProvinceCode' => '',
                        'countryCode' => '',
                        'countryName' => '',
                    ),                
                ),
                'carrierCode' => '',
                'OperatingCompanyOrCarrierDescription' => '',            
                'originAddress' => array(
                    'stateOrProvinceCode' => '',
                    'countryCode' => '',
                    'countryName' => '',
                ),            
                'destinationAddress' => array(
                    'stateOrProvinceCode' => '',
                    'countryCode' => '',
                    'countryName' => '',
                ),
                'events' => array(
                    array(
                        'dateTime' => '',                    
                        'eventCode' => '',
                        'eventDescription' => '',
                        'address' => array(
                            'postalCode' => '',
                            'stateOrProvinceCode' => '',
                            'countryName' => '',
                            'countryCode' => '',                                                
                        ),                    
                    )
                ),
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
                        'message' => '',
                    ),                
                    'numberOfPieces' => '',
                    'packageSequenceNumber' => '',                
                    'packaging' => '',
                    'service' =>  array(                    
                        'description' => ''                    
                    ),                
                    'pickupDateTime' => '', //shipTimestamp on fedex                                
                    'lastUpdated' => '', //ActualDeliveryTimestamp on fedex
                )
            )
        ),        
    );
    
    public $errorSkeleton = array(
        'status' => array(
            'code' => self::RESPONSE_STATUS_ERROR_CODE,
            'dateTime' => '',
        ),
        'error' => array (
            'code' => '',
            'message' => '',
            'description' => '',
            'errors' => array(
                'code' => '',
                'field' => '',
                'message' => ''
            ),
        ),
    );
                
    public $searchKey;
    
    public $params;
    
    public $serviceLocator;
    
    public $service;
    
    public $tracking;
            
    public function getTracking($params)
    {
        return $this->tracking;
    }
    
    public function http()
    {
        
    }
    
    public function checkSearchKeyOwner($searchkey) 
    {
        $return = false;        
        if ($this->isSearchKeyOwner($searchkey)) {
            $this->searchKey = $searchkey;
            $config = $this->serviceLocator->get('config');                
            $this->setWs($config['carrier'][$this::ALIAS]['tracking']);
            
            $return = true;
        }
        
        return $return;
    }
    
    public function saveSearch($params)
    {        
        //$apikeyId = $this->getApikeyService()->getRepository()->getByKey($params['key']);                        
        $serviceApikeyData = array(
            'serviceId' => $params['serviceId'],
            'apikeyId' => $params['apikeyId'],
        );
        
        $serviceApikeyId = $this->getServiceApikeyService()
                ->save($serviceApikeyData);
                        
        $searchData = array(
            'carrierId' => $this::DB_ID,
            'serviceApikeyId' => $serviceApikeyId,
            'trackingKey' => $params['searchKey'],
            'ip' => \Util\Common\Util::getIpClient(),                        
        );
        
        return $this->getSearchService()->getRepository()->save($searchData);
    }
            
    public function updateSearch($searchId)
    {        
        foreach ($this->tracking['trackingDetails'] as $tracking) {
            $trackData = array(
                'searchId' => $searchId,
                'carrierId' => $this::DB_ID,
                'trackingKey' => $tracking['trackingKey'],
                'statusCreationDateTime' =>$tracking['statusDetail']['creationDateTime'],
                'statusCode' => $tracking['statusDetail']['code'],
                'statusDescription' => $tracking['statusDetail']['description'],
                'statusLocStateOrProvinceCode' => $tracking['statusDetail']['location']['stateOrProvinceCode'],
                'statusLocCountryCode' => $tracking['statusDetail']['location']['countryCode'],
                'statusLocCountryName' => $tracking['statusDetail']['location']['countryName'],
                'track' => json_encode($this->tracking),
            );

            $this->getTrackService()->getRepository()->save($trackData);
        }
                
        $this->tracking['status']['referenceId'] = str_pad($searchId, 10, '0', STR_PAD_LEFT);
    }
    
    public function getLastValidTrack($trackingKey)
    {
        $return = array();
        
        $carrierId = $this::DB_ID;
        $config = $this->getServiceLocator()->get('config');
        $trackLifeTime = $config['carrier']['trackLifeTime'];
        
        $trackData = $this->getTrackService()->getRepository()
                ->getLastValidTrack($trackingKey, $carrierId, $trackLifeTime);
        
        if (!empty($trackData)) {
            $return = json_decode($trackData['track']);
        }
        
        return $return;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }
    
    public function getParams($params)
    {
        return  $this->params;
    }
    
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    
    /**
     * @return SearchService
     */
    public function getServiceService()
    {
        return $this->serviceLocator->get('Model\ServiceService');
    }
    
    /**
     * @return SearchService
     */
    public function getSearchService()
    {
        return $this->serviceLocator->get('Model\SearchService');
    }
    
    /**
     * @return ApikeyService
     */
    public function getApikeyService()
    {
        return $this->serviceLocator->get('Model\ApikeyService');
    }
    
    /**
     * @return TrackService
     */
    public function getTrackService()
    {
        return $this->serviceLocator->get('Model\TrackService');
    }
    
    /**
     * @return ServiceApikeyService
     */
    public function getServiceApikeyService()
    {
        return $this->serviceLocator->get('Model\ServiceApikeyService');
    }
    
    abstract public function isSearchKeyOwner($searchkey);
    
    abstract function setWs($wsConfig);    
} 