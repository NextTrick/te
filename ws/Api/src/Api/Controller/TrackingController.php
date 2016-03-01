<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Api\Model\Service\TrackingService;

class TrackingController extends AbstractRestfulController
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
        $trackingService = $this->getTrackingService();        
        return $trackingService->getTracking(array('searchKey' => $id)); exit; 
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
