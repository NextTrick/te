<?php
namespace Util;
use Zend\ServiceManager\ServiceLocatorInterface;

return array(
//    'factories' => array(
//        'currentUrl' => function (ServiceLocatorInterface $sm) {
//           $match = $sm->getServiceLocator()->get('application')->getMvcEvent()->getRouteMatch();
//
//           $viewHelper = new View\Helper\CurrentUrl($match);
//           return $viewHelper;
//        },
//        'baseFullPath' => function (ServiceLocatorInterface $sm) { 
//            
//           $viewHelper = new View\Helper\BaseFullPath($sm);
//           return $viewHelper;
//        },
//        'server' => function(ServiceLocatorInterface $sm) {
//            $config = $sm->getServiceLocator()->get('config');
//            $servers = $config['servers'];
//            
//            return new \Util\View\Helper\Server($servers);
//        },
//        'domain' => function(ServiceLocatorInterface $sm) {
//            $config = $sm->getServiceLocator()->get('config');
//            $servers = $config['servers'];
//            
//            return new \Util\View\Helper\Domain($servers);
//        },            
//        'pathString' => function (ServiceLocatorInterface $sm) {             
//           $viewHelper = new View\Helper\PathString();
//           $viewHelper->setServiceMananger($sm);
//           return $viewHelper;
//        },
//        'flashMessenger' => function (ServiceLocatorInterface $sm) { 
//                $flash = $sm->getServiceLocator()->get('ControllerPluginManager')->get('flashmessenger');
//                $messages  = new \Util\View\Helper\FlashMessenger();
//                $messages->setFlashMessenger($flash);
//                return $messages;
//        }, 
//        'relativeDate' => function (ServiceLocatorInterface $sm) {             
//           $viewHelper = new View\Helper\RelativeDate($sm);
//           return $viewHelper;
//        },            
//    ),
);
