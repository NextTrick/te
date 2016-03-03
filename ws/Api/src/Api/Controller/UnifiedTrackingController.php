<?php

namespace Api\Controller;

class UnifiedTrackingController extends BaseController
{
     public function getList()
    {
        return array();        
    }

    public function get($id)
    {
        return array();  
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