<?php

namespace Api\Controller;

class MultitrackingController extends BaseController
{
    public function getList()
    {
       return array();        
    }

    public function get($id)
    {
        var_dump('aa');exit;
        $trackingService = $this->getTrackingService();        
        return $trackingService->getMuliTracking(array('searchKey' => $id)); exit; 
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
     * @return TrackingService
     */
    public function getTrackingService()
    {
        return $this->getServiceLocator()->get('Api\Model\TrackingService');
    }
}