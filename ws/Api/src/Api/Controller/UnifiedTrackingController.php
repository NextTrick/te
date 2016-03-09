<?php

namespace Api\Controller;

use Api\Controller\Base\BaseRestfulController;
use Service\Model\Repository\ServiceRepository;

class UnifiedTrackingController extends BaseRestfulController
{
    const ENDPOINT_TRACKING_ID = ServiceRepository::ENDPOINT_TRACKING_ID;
    
    public function getList()
    {
        return array();        
    }

    public function get($id)
    {
        return array();  
    }

    public function create($params)
    {
        $params['serviceId'] = self::ENDPOINT_TRACKING_ID;
        $params['apikeyId'] = $this->apikeyId;
        $params['trackings'] = $params['trackings'];
        $params['profileId'] = $this->apikeyData['profileId'];
        
        return $this->getMultiTrackingService()
                   ->insertMultiTracking($params);        
    }

    public function update($token, $params)
    {
        $params['serviceId'] = self::ENDPOINT_TRACKING_ID;
        $params['apikeyId'] = $this->apikeyId;     
        $params['trackings'] = $params['trackings'];
        $params['profileId'] = $this->apikeyData['profileId'];
       
        return $this->getMultiTrackingService()
                   ->updateMultiTracking($token, $params); 
    }

    public function delete($token)
    {
        return $this->getMultiTrackingService()->deleteMultiTracking($token);
    }
   
    /**
     * @return \Service\Model\Service\MultiTrackingService
     */
    public function getMultiTrackingService()
    {
        return $this->getServiceLocator()->get('Model\MultiTrackingService');
    }
}