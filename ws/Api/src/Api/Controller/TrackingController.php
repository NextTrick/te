<?php

namespace Api\Controller;

use Api\Model\Service\TrackingService;
use Api\Controller\Base\BaseRestfulController;
use Service\Model\Repository\ServiceRepository;

class TrackingController extends BaseRestfulController
{
    public function getList()
    {
        $params = $this->params()->fromRoute();
        $params = array(
            'searchKey' => '23432432',
            'key' => '432J2H2H11G11F1F1G11G1',
        );
        $trackingService = $this->getTrackingService();        
        return $trackingService->getTracking($params); exit;        
    }

    public function get($id)
    {        
        $params = $this->getRequestParams();      
        $params['serviceId'] = ServiceRepository::ENDPOINT_TRACKING_ID;
        $params['serarchKey'] = $params['id'];
        $trackingService = $this->getTrackingService();
        
        return $trackingService->getTracking($params);
    }

    public function create($data)
    {
       
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
