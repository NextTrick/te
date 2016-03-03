<?php

namespace Api\Controller\Base;

use Zend\Mvc\Controller\AbstractRestfulController;

class BaseRestfulController extends AbstractRestfulController
{
    public function getList()
    {
               
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
    
    public function getRequestParams()
    {
        $paramsRoute = $this->params()->fromRoute();
        $paramsPost = $this->params()->fromPost();
        $paramsQuery = $this->params()->fromQuery();
        
        return array_merge($paramsRoute, $paramsPost, $paramsQuery);
    }
}
