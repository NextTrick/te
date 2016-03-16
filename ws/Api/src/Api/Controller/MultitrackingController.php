<?php

namespace Api\Controller;
use Api\Controller\Base\BaseRestfulController;
use Service\Model\Repository\ServiceRepository;

class MultitrackingController extends BaseRestfulController
{
    const ENDPOINT_MULTITRACKING_ID = ServiceRepository::ENDPOINT_MULTITRACKING_ID;
    
    public function getList()
    {
       return array();        
    }

    public function get($id)
    {
        $params = $this->getRequestParams();
        $params['serviceId'] = self::ENDPOINT_MULTITRACKING_ID;
        $params['searchKey'] = $id;                
        $params['apikeyId'] = $this->apikeyId;
        $params['profileId'] = $this->apikeyData['profileId'];
        
        $trackingService = $this->getTrackingService(); 
        return $trackingService->getMultiTracking($params);
    }

    public function create($params)
    {
        $params = $this->getRequestParams();
        $params['serviceId'] = self::ENDPOINT_MULTITRACKING_ID;
        $params['apikeyId'] = $this->apikeyId;
        $params['trackings'] = $params['trackings'];
        $params['profileId'] = $this->apikeyData['profileId'];
        
        return $this->getMultiTrackingService()
                   ->insertMultiTracking($params);        
    }

    public function update($token, $params)
    {
        $params = $this->getRequestParams();
        $params['serviceId'] = self::ENDPOINT_MULTITRACKING_ID;
        $params['apikeyId'] = $this->apikeyId;     
        $params['trackings'] = $params['trackings'];
        $params['profileId'] = $this->apikeyData['profileId'];
       
        return $this->getMultiTrackingService()
                   ->updateMultiTracking($params['param2'], $params); 
    }

    public function delete($token)
    {
        $params = $this->getRequestParams();
        $params['serviceId'] = self::ENDPOINT_MULTITRACKING_ID;
        $params['apikeyId'] = $this->apikeyId;     
        $params['profileId'] = $this->apikeyData['profileId'];
        return $this->getMultiTrackingService()->deleteMultiTracking($params['param2'], $params);
    }
    
    /**
     * @return \Api\Model\Service\TrackingService
     */
    public function getTrackingService()
    {
        return $this->getServiceLocator()->get('Api\Model\TrackingService');
    }   
    
    /**
     * @return \Service\Model\Service\MultiTrackingService
     */
    public function getMultiTrackingService()
    {
        return $this->getServiceLocator()->get('Model\MultiTrackingService');
    }
}