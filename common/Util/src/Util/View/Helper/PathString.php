<?php

namespace Util\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\Enum\SiteEnum;

class PathString extends AbstractHelper
{    
    const QUERY = 'qs';
    
    const PATH = 'ph';
    
    /**
     * Base path
     *
     * @var string
     */
    protected $basePath;
    
    public $url;
    
    /**
     *
     * @var Zend\ServiceManager\ServiceLocatorInterface 
     */
    protected $_sm;
    
    public function __construct()
    {
       return $this;
    }
        
    public function setServiceMananger(ServiceLocatorInterface $sm)
    {
        $this->_sm = $sm;
        $uri = $sm->getServiceLocator()->get('application')
                ->getMvcEvent()->getRequest()->getUri();
        
        $basePath = $currentHost = $uri->getScheme() 
                . '://' . $uri->getHost();                
        
        $this->setBasePath($basePath);
    }
    
    public function setUri()
    {        
        $uri = $this->_sm->getServiceLocator()->get('application')
                ->getMvcEvent()->getRequest()->getUri();
        
        $basePath = $currentHost = $uri->getScheme() 
                . '://' . $uri->getHost() . $uri->getPath();
        
        $this->setBasePath($basePath);
                
        return $this;
    }
    
    public function setPath($path)
    {                        
        $this->basePath = $this->basePath . '/' . ltrim($path, '/');  
                
        return $this;
    }
                
    /**
     * Asigna valores para el armado de la url
     * 
     * @param type $values
     * @param type $tp Tipo de url path o QueryString
     * @param type $addPathString flag que determina si se agrega o  
     * @param type $merge
     * @return \Util\View\Helper\PathString
     */
    public function setValues($values = array(), $tp = self::QUERY, $addPathString = true, $merge = true)
    {
        $qs = '';
        $path = '';
        if ($tp == self::QUERY) {
            $qs = $this->getPathString($values, $tp, $merge);
            if ($addPathString == true) {
                $path = $this->getPathString(array(), self::PATH);                
            }
        } else {
            $path = $this->getPathString($values, $tp, $merge);
            if ($addPathString == true) {
                $qs = $this->getPathString(array(), self::QUERY);                
            }
        }
                
        $this->url = $this->basePath . $path . $qs;
        
        return $this;
    }
    
    /**
     * Arma la url según parámetros
     * 
     * @param type $values
     * @param type $tp
     * @param type $merge
     * @return string
     */
    public function getPathString($values, $tp, $merge = true)
    {                
        $qsSession = new Container('pathAndQueryString');
        $return = '';
        if ($merge == true) {
            if (!empty($qsSession->data[$tp])) {
                $values = array_merge($qsSession->data[$tp], $values);
            }
        }
        
        if ($tp == self::PATH) {                         
                foreach ($values as $key => $value) {
                    $return .= "/$value";
                }            
        } else {
            if (!empty($values)) {
                $return = '?' . http_build_query($values);            
            }            
        }
               
        return $return;
    }
    
    public function getPathStringDEPRECATED($tp)
    {
        $qsSession = new Container('pathAndQueryString');
        $return = '';
        if ($tp == self::PATH) {
            if (!empty($qsSession->data[$tp])) {
                foreach ($qsSession->data[self::PATH] as $key => $value) {
                    $return .= "/$value";
                }
            }
        } else {
            if (!empty($qsSession->data[$tp])) {
                $return = '?' . http_build_query($qsSession->data[self::QUERY]);
            }
        }
               
        return $return;
    }
           
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * Verifica si los parametros indicados estan activos en sessión
     * 
     * @param type $variable
     * @param type $value
     * @param type $tp
     * @return boolean
     */
    public function isActive($variable, $value, $tp = self::QUERY)
    {
        $qsSession = new Container('pathAndQueryString');
                
        if (!empty($qsSession->data[$tp][$variable])) {
            if (strtolower($qsSession->data[$tp][$variable]) == strtolower($value)) {
               return true; 
            }
        } else {
            if (in_array(strtolower($value), \Application\Model\Enum\SiteEnum::getDefaultFilterOptions())) {
                return true;
            }
        }
        
        return false;       
    }
    
    /**
     * Verifica si una variable existe en sesión 
     * 
     * @param type $variable
     * @param type $tp
     * @return boolean
     */
    public function exists($variable, $tp = self::QUERY)
    {
        $qsSession = new Container('pathAndQueryString');
        
        if (!empty($qsSession->data[$tp][$variable])) {
            return true;
        }
        
        return false;
    }
               
    /**
     * Set the base path.
     *
     * @param  string $basePath
     * @return self
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '/');
        return $this;
    }
    
    /**
     * Borra las variables de sessión según parámetros
     * 
     * @param type $tp
     * @param type $variable
     */
    public function cleanData($tp = null, $variable = null)
    {
        $qsSession = new Container('pathAndQueryString');
        
        if ($tp != null) {
            unset($qsSession->data[$tp]);
        } else if ($variable != null) {
            unset($qsSession->data[$tp][$variable]);
        } else {
            unset($qsSession->data);
        }                
    }
}