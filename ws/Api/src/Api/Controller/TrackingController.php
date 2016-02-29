<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

class TrackingController extends AbstractRestfulController
{
    public function getList()
    {
        return array('');
    }

    public function get($id)
    {
        $tracking = $this->getServiceLocator()->get('Api\Model\Service\TrackingService');
        return $tracking->getTracking(array('searchKey' => $id));
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
}
