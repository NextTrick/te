<?php

namespace Api\Controller;

use Api\Model\Service\TrackingService;
use Api\Controller\Base\BaseRestfulController;
use Service\Model\Repository\ServiceRepository;

class TrackingController extends BaseRestfulController
{    
    const ENDPOINT_TRACKING_ID = ServiceRepository::ENDPOINT_TRACKING_ID;
    
    public function getList()
    {
        return array();
    }

    public function get($id)
    {        
        $params = $this->getRequestParams();      
        $params['serviceId'] = self::ENDPOINT_TRACKING_ID;
        $params['searchKey'] = $id;                
        $params['apikeyId'] = $this->apikeyId;
        $params['profileId'] = $this->apikeyData['profileId'];
        
        $trackingService = $this->getTrackingService();                  
        return $trackingService->getTracking($params);        
    }

    public function create($data)
    {
       return array();
    }

    public function update($id, $data)
    {

    }

    public function delete($id)
    {

    }
    /**
     * @return TrackingService
     */
    public function getTrackingService()
    {
        return $this->getServiceLocator()->get('Api\Model\TrackingService');
    }
}
