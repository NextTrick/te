<?php

namespace Api;

use Bongo\Model\BpAbandon;
use Bongo\Model\ConnectLandedCost;
use Bongo\Model\LogPagoError;
use Bongo\Model\MBproducts;
use Bongo\Model\Partners;
use Bongo\ServiceManager\ServiceManagerConfig;
use Zend\EventManager\EventInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(EventInterface $e)
    {
        $application = $e->getTarget();
        $eventManager = $application->getEventManager();
        $services = $application->getServiceManager();
        $services->get('Server');
        
        $app = $e->getParam('application');
        $app->getEventManager()->attach('dispatch', array($this,'initEnvironment'), 100);        
//        $app->getEventManager()->attach('dispatch.error', array($this,'initError'), 100);
        
        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractController',
            'dispatch',
            function($e) {
                $controller = $e->getTarget();
                $controllerClass = get_class($controller);
                $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
                $config = $e->getApplication()->getServiceManager()->get('config');
                if (isset($config['module_layouts'][$moduleNamespace])) {
                    $controller->layout($config['module_layouts'][$moduleNamespace]);
                }
            },
            100
        );

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);       
    }

    public function getConfig()
    {
        return include __DIR__ . '/configs/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        $services = new ServiceManagerConfig();
        $services->setService(
            array(
                'Bongo\Model\MBproducts' => function($sm) {
                    $adapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new MBproducts($adapter, $sm);
                },
                'Bongo\Model\BpAbandon' => function($sm) {
                    $adapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new BpAbandon($adapter, $sm);
                },
                'Bongo\Model\ConnectLandedCost' => function($sm) {
                    $adapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new ConnectLandedCost($adapter, $sm);
                },
                'Bongo\Model\Partners' => function($sm) {
                    $adapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new Partners($adapter, $sm);
                },
                'Bongo\Model\LogPagoError' => function($sm) {
                    $adapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new LogPagoError($adapter, $sm);
                }                
            ));

        return $services;
    }
    
    public function initEnvironment($e)
    {           
       $app = $e->getParam('application');
       $config = $app->getServiceManager()->get('config');
       $settings = $config['php']['settings'];
       
       foreach ($settings as $key => $setting) {
           if ($key == 'error_reporting') {
               error_reporting($setting);
               continue;
           }

           ini_set($key, $setting);
       }
    }
    
    /* public function initError(MvcEvent $e)
    {
        $app = $e->getParam('application');        
        $config = $app->getServiceManager()->get('config');
        
        if (!is_null($e->getParam('exception'))) { 
            $exception = $e->getParam('exception');
            $logConfig = $config['error']; 
            $errorHandlerService = $app->getServiceManager()->get('Bongo\ErrorHandler');
                        
            if ($logConfig['local_log']) {
                $errorHandlerService->logException($exception);            
            }
         
            if ($logConfig['send_mail']) {
                try {
                    \Util\Common\Email::reportException($exception);                    
                } catch (\Exception $exception) {
                    $errorHandlerService->logException($exception); 
                }
            }
        }       
    }*/
}
