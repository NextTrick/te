<?php

namespace Api\Controller;

class MultitrackingController extends BaseController
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
        $params = $this->params()->fromRoute();
        var_dump($params); exit;
        $trackingService = $this->getTrackingService();        
        return $trackingService->getMuliTracking(array('searchKey' => $id)); exit; 
    }

    public function create($data)
    {
        $trackingService = $this->getTrackingService();        
        return $trackingService->createtMuliTracking(array('searchKey' => $id)); exit;
    }

    public function update($id, $data)
    {
        $trackingService = $this->getTrackingService();        
        return $trackingService->updatetMuliTracking(array('searchKey' => $id)); exit;
    }

    public function delete($id)
    {
        $trackingService = $this->getTrackingService();        
        return $trackingService->deleteMuliTracking(array('searchKey' => $id)); exit;
    }
    
    /**
     * @return TrackingService
     */
    public function getTrackingService()
    {
        return $this->getServiceLocator()->get('Api\Model\TrackingService');
    }
}