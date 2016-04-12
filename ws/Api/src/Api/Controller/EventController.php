<?php

namespace Api\Controller;

use Api\Controller\Base\BaseRestfulController;

class EventController extends BaseRestfulController
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
        return $this->getEventService()->saveEvent($params);
    }

    public function update($token, $params)
    {
       $params['eventId'] = $token;
       return $this->getEventService()->saveEvent($params);
    }

    public function delete($token)
    {
        $params['eventId'] = $token;
       return $this->getEventService()->saveEvent($params);
    }
    
    /**
     * @return \Track\Model\Service\EventService
     */
    public function getEventService()
    {
        return $this->getServiceLocator()->get('Track\Model\Service\EventService');
    }
}