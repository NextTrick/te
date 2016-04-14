<?php

namespace Api\Controller;

use Api\Model\Service\TrackingService;
use Api\Controller\Base\BaseRestfulController;
use Service\Model\Repository\ServiceRepository;
use Track\Model\Service\TrackService;

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
        $params['serviceApikeyId'] = $this->serviceApikeyId;
        $trackingService = $this->getTrackingService();                  
        return $trackingService->getTracking($params);        
    }

    public function create($data)
    {
        $params = $this->getRequestParams();      
        $params['serviceId'] = self::ENDPOINT_TRACKING_ID;        
        $params['apikeyId'] = $this->apikeyId;
        $params['serviceApikeyId'] = $this->serviceApikeyId;
        $trackService = $this->getTrackService();
        
        return $trackService->create($params);
    }

    public function update($id, $data)
    {
        $params = $this->getRequestParams();      
        $params['serviceId'] = self::ENDPOINT_TRACKING_ID;        
        $params['apikeyId'] = $this->apikeyId;
        $params['serviceApikeyId'] = $this->serviceApikeyId;
        $trackService = $this->getTrackService();
        
        return $trackService->update($params);
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
    
    /**
     * @return TrackService
     */
    public function getTrackService()
    {
        return $this->getServiceLocator()->get('Model\TrackService');
    }
}
