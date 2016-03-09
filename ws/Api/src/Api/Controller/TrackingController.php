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
        throw new \Exception('error');
        $params = $this->getRequestParams();      
        $params['serviceId'] = ServiceRepository::ENDPOINT_TRACKING_ID;
        $params['searchKey'] = $id;                
        $params['apikeyId'] = $this->apikeyId;
        
        //TODO: delete test data
//        $params['searchKey'] = '149331877648230';
//        $params['key'] = '2342FF2343223FFFSS';
//        $params['apikeyId'] = 1;
        
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
