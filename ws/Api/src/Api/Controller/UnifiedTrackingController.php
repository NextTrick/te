<?php
namespace Api\Controller;

use Api\Controller\Base\BaseRestfulController;
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

    public function create($data)
    {
        return $this->getMultiTrackingService()
                   ->insertMultiTracking($data, $this->apikeyId);        
    }

    public function update($id, $data)
    {

    }

    public function delete($id)
    {

    }
   
    /**
     * @return \Service\Model\Service\MultiTrackingService
     */
    public function getMultiTrackingService()
    {
        return $this->getServiceLocator()->get('Model\MultiTrackingService');
    }
}