<?php
namespace Track\Model\Request\Base;

abstract class Request
{
    public $errors;
    
    public $params;
    
    public function __construct($params) 
    {
        $this->params = $params;
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
    
    abstract public function checkRequiredParams();
    
    abstract public function getSkeleton();
}