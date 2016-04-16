<?php
namespace Track\Model\Request\Base;
use Service\Model\Service\RequestService;

abstract class Request
{
    public $errors;
    
    public $params;
    
    public $method;
    
    public function __construct($params, $method, $serviceLocator) 
    {
        $this->params = $params;        
        $this->method = $method;        
        $this->_sl = $serviceLocator;
        
        $this->saveRequest();
    }
        
    public function hasErrors()
    {
        $return = false;
        if (empty($this->errors)) {
            $return = true;
        }
        
        return $return;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function serviceLocator()
    {
        return $this->_sl;
    }
    
    public function saveRequest()
    {
        return $this->getRequestService()
                ->save($this->params, $this->method);
    }
    
    /**
     * @return RequestService
     */
    protected function getRequestService()
    {
        return $this->getServiceLocator()->get('Model\ServiceRequestService');
    }
    
    abstract public function checkRequiredParams();
    
    abstract public function getCreateSkeleton();
    
    abstract public function getUpdateSkeleton();
    
    abstract public function getDeleteSkeleton();
}