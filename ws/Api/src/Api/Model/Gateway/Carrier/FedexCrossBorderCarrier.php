<?php

namespace Api\Model\Gateway\Carrier;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Service\Model\Repository\ServiceRepository;
use Api\Controller\Base\BaseResponse;

class FedexCrossBorderCarrier extends CarrierAbstract
{      
    const ALIAS = 'FedexCrossBorder';
    const ERROR_CODE_905 = 905;
    const ERROR_MESSAGE_905 = 'Trackings no encontrados.';

    public $serviceLocator;
    
    public function __construct($serviceLocator) 
    {        
        $this->serviceLocator = $serviceLocator;      
    }
    
    public function setWs($wsConfig)
    {
        
    }
    
    public function getMultiTracking($params)
    {       
        $paramsTrack = array();
        $response = array(
            'status' => array(
                'code' => BaseResponse::RESPONSE_STATUS_SUCCESS_CODE,
                'dateTime' => '',
                'referenceId' => ''
            )
        );
        $responseTrackings = $this->getMultiTrackingService()
                ->getRepository()->getByToken($params['searchKey']);
        $trackings = json_decode($responseTrackings['trackingKeys'], TRUE);
        $trackingService = $this->getTrackingService(); 
        $trackingvalido = FALSE;
        $trackingDetails = array();
        if(!empty($trackings)) {
            foreach ($trackings as $tracking) {
                $paramsTrack['serviceId'] = ServiceRepository::ENDPOINT_TRACKING_ID;
                $paramsTrack['searchKey'] = $tracking;                
                $paramsTrack['apikeyId'] = $params['apikeyId'];
                $responseTracking = $trackingService->getTracking($paramsTrack);  
                if($responseTracking['status']['code']) {
                    $responseTracking = TRUE;
                    $trackingDetails[] = array_values($responseTracking['trackingDetails']);
                }
            }   
        }
        if(!$trackingvalido) {
            $response = BaseResponse::getErrorSkeleton();
            $response['error']['message'] = self::ERROR_MESSAGE_905;
            $response['error']['code'] = self::ERROR_CODE_905;
        } else {
            $response['trackingDetails'] = array_values($trackingDetails); 
            $response = array_merge(self::getTrackingSkeleton(), $response);
        }
        return $response;
    }
            
    public function isSearchKeyOwner($searchkey)
    {   
        $response = false;
        if(strlen($searchkey) == 40) {
            $response = true;
        }
        return $response;
    }
    
    /**
     * 
     * @return \Service\Model\Service\MultiTrackingService
     */
    public function getMultiTrackingService()
    {
        return $this->serviceLocator->get('Model\MultiTrackingService');
    }
    
     /**
     * @return \Api\Model\Service\TrackingService
     */
    public function getTrackingService()
    {
        return $this->getServiceLocator()->get('Api\Model\TrackingService');
    }  

}
