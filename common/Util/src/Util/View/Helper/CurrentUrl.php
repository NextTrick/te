<?php

namespace Util\View\Helper;

use Zend\View\Helper\AbstractHelper;

class CurrentUrl extends AbstractHelper
{
    private $_url;
    private $_moduleName;
    private $_controllerName;
    private $_actionName;
    private $_route;
    
    public function __invoke()
    {
        $path = explode("\\", $this->_url);
        
        
        $this->_moduleName = $path[0];
        $this->_controllerName = @$path[2];
        $this->_actionName = @$this->_route->getParam('action');
        
        return $this;
    }

    public function __construct($route)
    {
        $this->_route = $route;
        $this->_url = strtolower( $route->getParam('controller') );
    }
    
    public function getModuleName()
    {
        return $this->_moduleName;
    }
    
    public function getControllerName()
    {
        return $this->_controllerName;
    }
    
    public function getActionName()
    {
        return $this->_actionName;
    }
    
    public function getUrl()
    {
        return "{$this->_moduleName}/{$this->_controllerName}/{$this->_actionName}";
    }
    
    public function getParams()
    {
        return $this->_route->getParams();
    }
    
    public function getParamsOutPath()
    {
        $params = $this->getParams();
        unset($params['__NAMESPACE__'],$params['__CONTROLLER__'],$params['module'],$params['controller'],$params['action']);
        return $params;
    }  
    
    public function getUrlFull($params = array())
    {
        $allParams = array_merge($this->getParamsOutPath(), $params);
        $url = '/' . $this->getUrl();
        if (!empty($allParams)) {
            foreach ($allParams as $key => $value) {
                $url.= '/' . $key . '/' . $value;
            }         
        } 
        
        return $url;        
    }  
}