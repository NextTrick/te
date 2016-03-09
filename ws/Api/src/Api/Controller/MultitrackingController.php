<?php

namespace Api\Controller;
use Api\Controller\Base\BaseRestfulController;
use Service\Model\Repository\ServiceRepository;

class MultitrackingController extends BaseRestfulController
{
    const ENDPOINT_TRACKING_ID = ServiceRepository::ENDPOINT_MULTITRACKING_ID;
    
    public function getList()
    {
       return array();        
    }

    public function get($id)
    {
        $params['serviceId'] = self::ENDPOINT_MULTITRACKING_ID;
        $params['searchKey'] = $id;                
        $params['apikeyId'] = $this->apikeyId;
        $params['profileId'] = $this->apikeyData['profileId'];
        
        $trackingService = $this->getTrackingService(); 
        return $trackingService->getMultiTracking($params);
    }

    public function create($data)
    {
        return array();
    }

    public function update($id, $data)
    {
        return array();
    }

    public function delete($id)
    {
        return array();
    }
    
    /**
     * @return \Api\Model\Service\TrackingService
     */
    public function getTrackingService()
    {
        return $this->getServiceLocator()->get('Api\Model\TrackingService');
    }   
}