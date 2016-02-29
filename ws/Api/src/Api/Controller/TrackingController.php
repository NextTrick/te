<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Api\Model\Service\TrackingService;

class TrackingController extends AbstractRestfulController
{
    public function getList()
    {
        $params = $this->params()->fromRoute();
        
        $trackingService = $this->getTrackingService();        
        return $trackingService->getTracking($params); exit;        
    }

    public function get($id)
    {

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
        return $this->get('Api\Model\TrackingService');
    }
}
