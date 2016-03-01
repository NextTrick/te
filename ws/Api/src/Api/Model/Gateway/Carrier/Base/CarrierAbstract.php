<?php

namespace Api\Model\Gateway\Carrier\Base; 

use Api\Model\Gateway\Carrier\Base\CarrierInterface;
use Search\Model\Service\SearchService;
use Statistic\Model\Service\ServiceApikeyService;

abstract class CarrierAbstract implements CarrierInterface
{
    public $trackingSkeleton = array(
        'serviceHeader' => array(
            'date' => '',
            'referenceId' => ''
        ),        
        'trackingDetails' => array(
            'trackingNumber' => '',
            'statusDetail' => array(
                'creationTime' => '',
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
            'destionationAddress' => array(
                'StateOrProvinceCode' => '',
                'countryCode' => '',
                'countryName' => '',
            ),
            'events' => array(
                array(
                    'date' => '',                    
                    'eventCode' => '',
                    'eventDescription' => '',
                    'address' => array(
                        'postalCode' => '',
                        'StateOrProvinceCode' => '',
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
                    'Message' => '',
                ),                
                'numberPieces' => '',
                'PackageSequenceNumber' => '',                
                'packaging' => '',
                'service' =>  array(                    
                    'description' => ''                    
                ),                
                'pickupDate' => '', //shipTimestamp on fedex                                
                'lastUpdated' => '', //ActualDeliveryTimestamp on fedex
            )
        ),        
    );
    
    public $errorSkeleton = array(
        'code' => '',
        'message' => '',
        'description' => '',
        'errors' => array(
            'code' => '',
            'field' => '',
            'message' => ''
        ),
    );
    
    public $tracking = array(
        'header' => array(
            'carrier' => '',
            'date' => '1/4/2014 1:22 AM ',
            'from' => '',
            'to' => '',),
        'events' => array(
            array(
                'date' => '',
                'time' => '',
                'areaCode' => '',
                'areaDetail' => '',
                'eventCode' => '',
                'eventDetail' => '',
            )
        ),
        'shipmentInfo' => array(
            'tracking' => '',
            'notification' => '',
            'numberPieces' => '',
            'packageNumber' => '',
            'packaging' => '',
            'pickupDate' => '',
            'service' => '',
            'statusMessage' => '',
            'weight' => '',
            'lastUpdated' => '',
        )
    );
            
    public $searchKey;
    
    public $params;
    
    public $serviceLocator;
    
    public $service;
            
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
        $serviceApikeyData = array(
            'serviceId' => 1, //TODO: Obtener dinamicamente
            'apikeyId' => 1, //TODO: Obtener dinamicamente            
        );
        
        $serviceApikeyId = $this->getServiceApikeyService()
                ->save($serviceApikeyData);
                        
        $searchData = array(
            'carrierId' => 1, //TODO: Obtener dinamicamente
            'serviceApikeyId' => $serviceApikeyId,
            'trackingId' => $params['searchKey'],
            'ip' => \Util\Common\Util::getIpClient(),                        
        );
        
        return $this->getSearchService()->save($searchData);
    }
    
    public function updateSearch($searchId, $updateData)
    {
        //TODO: LLENAR DATA en las tablas fcb_carrier_request y fcb_search_tracking
    }
    
    public function setParams($params)
    {
        $this->params = $params;
    }
    
    public function getParams($params)
    {
        return  $this->params;
    }
    
    /**
     * @return SearchService
     */
    public function getSearchService()
    {
        return $this->serviceLocator->get('Model\SearchService');
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