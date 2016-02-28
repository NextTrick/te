<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

class TrackingController extends AbstractRestfulController
{
    public function getList()
    {
        $tracking = $this->getServiceLocator()->get('TrackingService');
        var_dump($tracking->getTracking());exit;
        return array('oks');
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
}
