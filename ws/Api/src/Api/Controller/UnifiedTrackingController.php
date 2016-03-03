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
        $this->getMultiTrackingService()
                   ->insertMultiTracking($data['trackings']);        
    }

    public function update($id, $data)
    {

    }

    public function delete($id)
    {

    }
   
    /**
     * @return \Service\Model\Service\ServiceMultiTrackingService
     */
    public function getMultiTrackingService()
    {
        return $this->getServiceLocator()->get('Service\Model\ServiceMultiTrackingService');
    }
}