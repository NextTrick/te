<?php
namespace Util\View\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\View\Helper\AbstractHelper;

class RelativeDate  extends AbstractHelper
{    
    private $_translator;
    
    public function __construct(ServiceLocatorInterface $sm)
    {
        $this->_translator = $sm->getServiceLocator()->get('translator');
        
    }
    
    public function __invoke($date)
    {   
        $relativeTime = \Util\Common\RelativeTime::getTime($date);
        $res = strpos($relativeTime, 'Just');
        if ($res !== FALSE) {
            $data = $this->_translator->translate($relativeTime);
        } else {
            $res = strpos($relativeTime, 'Yesterday');
            // Yesterday
            $arr = explode(' ', $relativeTime);
            if ($res !== FALSE) {
                $traducir = $arr[0] . ' ' . $arr[1];
                $translate = $this->_translator->translate($traducir);
                $data = $translate . ' ' . $arr[2];
            } else {
                $traducir = $arr[1] . ' ' . $arr[2];
                $translate = $this->_translator->translate($traducir);
                $data = $arr[0] . ' ' . $translate;
            }
        }
        
        return $data;
    }

}
