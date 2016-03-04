<?php

namespace Api\Controller;

use Api\Controller\Base\BaseRestfulController;
use Service\Model\Repository\ServiceRepository;

class UnifiedTrackingController extends BaseRestfulController
{
    public function getList()
    {
        return array();        
    }

    public function get($id)
    {
        return array();  
    }

    public function create($params)
    {
        $params['serviceId'] = ServiceRepository::ENDPOINT_TRACKING_ID;
        $params['apikeyId'] = $this->apikeyId;
        $params['trackings'] = $params['trackings'];
        return $this->getMultiTrackingService()
                   ->insertMultiTracking($params);        
    }

    public function update($toke, $data)
    {
        $params['serviceId'] = ServiceRepository::ENDPOINT_TRACKING_ID;
        $params['apikeyId'] = $this->apikeyId;
        $params['trackings'] = $params['trackings'];
        return $this->getMultiTrackingService()
                   ->insertMultiTracking($params); 
    }

    public function delete($multitrackingId)
    {
        return $this->getMultiTrackingService()->deleteMultiTracking($multitrackingId);
    }
   
    /**
     * @return \Service\Model\Service\MultiTrackingService
     */
    public function getMultiTrackingService()
    {
        return $this->getServiceLocator()->get('Model\MultiTrackingService');
    }
}