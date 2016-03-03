<?php

namespace Api\Controller;

use Api\Model\Service\TrackingService;
use Api\Controller\Base\BaseRestfulController;
use Service\Model\Repository\ServiceRepository;

class TrackingController extends BaseRestfulController
{
    
    public function getList()
    {
        return array();
    }

    public function get($id)
    {           
        $params = $this->getRequestParams();      
        $params['serviceId'] = ServiceRepository::ENDPOINT_TRACKING_ID;
        $params['searchKey'] = $params['id'];        
        $params['searchKey'] = '377101283611590';
        $params['apikeyId'] = 1;
        $params['key'] = '2342FF2343223FFFSS';
        $trackingService = $this->getTrackingService();  
        
        return $trackingService->getTracking($params);        
        var_dump($trackingService->getTracking($params)); exit;
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
