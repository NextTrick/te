<?php

namespace Util\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\Enum\SiteEnum;

class PathString extends AbstractPlugin
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
    
    public function __construct()
    {
       return $this;
    }
        
    public function setServiceMananger(ServiceLocatorInterface $sm)
    {
        $uri = $sm->getServiceLocator()->get('application')
                ->getMvcEvent()->getRequest()->getUri();
        
        $basePath = $currentHost = $uri->getScheme() 
                . '://' . $uri->getHost();                
        
        $this->setBasePath($basePath);
    }
    
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
     * 
     * @param type $variable
     * @param type $value
     * @param type $tp
     */
    public function setActiveValue($variable, $value, $tp = self::PATH)
    {
        $qsSession = new Container('pathAndQueryString');        
        if (empty($qsSession->data)) {
            $qsSession->data = array();
        }
        
        $qsSession->data[$tp][$variable] = $value;        
    }
    
    /**
     * Devuelve los variables guardadas en sessión
     * 
     * @return type
     */
    public function getData()
    {
        $qsSession = new Container('pathAndQueryString');        
        if (empty($qsSession->data)) {
            return array();
        }
        
        return $qsSession->data;
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
    
    /**
     * TODO:
     * 
     * Resetea las vaiables de sessión
     */
    public function resetValues()
    {
        $this->setActiveValue('mainFilter', \Application\Model\Enum\SiteEnum::FILTER_POPULAR);
        $this->setActiveValue('hashtag', SiteEnum::HASHTAG_DEFAULT);
        $this->setActiveValue('category', SiteEnum::CATEGORY_DEFAULT, \Util\View\Helper\PathString::QUERY);
    }
}