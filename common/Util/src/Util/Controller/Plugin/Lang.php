<?php

namespace Util\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class Lang extends AbstractPlugin// implements ServiceLocatorAwareInterface
{
    public $code;
    
    public $id;
    
    public $idioma;
    
    private $_ts;
    private $_is;
    const SESSION_NAME = 'langSession';

    public function __construct(ServiceLocatorInterface $sm)
    {
        $this->_ts = $sm->getServiceLocator()->get('translator');
        $this->_is = $sm->getServiceLocator()->get('\Idioma\Model\Service\IdiomaService');
         
        $this->init();
    }

    public function init()
    {
        $langSession = new Container(self::SESSION_NAME);
        if (empty($langSession->idioma)) {
            $langSession->idioma = $this->_is->getByCodigo($this->_detectIdiomaCode());            
        }
        $this->idioma =$langSession->idioma;
        $this->code = $this->idioma->getCodigo();
        $this->id = $this->idioma->getid();        
    }
    
    public function setIdiomaCode($idiomaCode = null)
    {        
        if (isset($idiomaCode)) {
            $langSession = new Container(self::SESSION_NAME); 
            $langSession->idioma = $this->_is->getByCodigo($idiomaCode);
        }  
        $this->init();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getCode()
    {
        return $this->code;
    }
    
    public function getIdioma()
    {
        return $this->idioma;
    }
    
    
    public function translateByCode($idiomaCode = null)
    {
        $dir = '';
        switch ($idiomaCode) {
            case 'en';
                $dir = APP_PATH . '/common/Util/translate/words.en.php';
                break;
            case 'es':
                $dir = APP_PATH . '/common/Util/translate/words.es.php';
                break;
            default:
                $dir = APP_PATH . '/common/Util/translate/words.en.php';
        }
        
        $type = 'phpArray';         
        $this->_ts->addTranslationFile($type, $dir);
        $this->_ts->setFallbackLocale('en_EN');

        if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $this->_ts
                ->setLocale(\Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']));
        }
    }

    public function initTranslate($idiomaCode = null)
    {
        $this->setIdiomaCode($idiomaCode);
        $this->translateByCode($this->getCode());
    }
    
    
    protected function _detectIdiomaCode()
    {        
        if (empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return 'en';
        }
        
        $langs = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        foreach ($langs as $lang) {
            $idioma = substr($lang, 0, 2);
        }
        return $idioma;
    }        
}