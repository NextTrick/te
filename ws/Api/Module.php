<?php

namespace Api;

use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Zend\ModuleManager\ModuleManager;
use Api\Controller\Base\BaseResponse;

class Module
{
    /**
     * @param MvcEvent $e
     */
    public function onBootstrap($e)
    {
        /** @var \Zend\ModuleManager\ModuleManager $moduleManager */
        $moduleManager = $e->getApplication()->getServiceManager()->get('modulemanager');
        /** @var \Zend\EventManager\SharedEventManager $sharedEvents */
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();

        $sharedEvents->attach('Zend\Mvc\Controller\AbstractRestfulController', MvcEvent::EVENT_DISPATCH, array($this, 'postProcess'), -100);
        $sharedEvents->attach('Zend\Mvc\Application', MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'errorProcess'), 999);
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
    public function init(ModuleManager $moduleManager) 
    {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
            $controller = $e->getTarget();
            $controller->init();
        }, 100);
    }

    public function getConfig()
    {
        return include __DIR__ . '/configs/module.config.php';
    }

    public function getServiceConfig()
    {
        return include __DIR__ . '/configs/service.config.php';
    }

    /**
     * @param MvcEvent $e
     * @return null|Response
     */
    public function postProcess(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        $formatter = $routeMatch->getParam('formatter', 'json');

        /** @var \Zend\Di\Di $di */
        $di = $e->getTarget()->getServiceLocator()->get('di');

        if ($formatter !== false) {
            if ($e->getResult() instanceof ViewModel) {
                if (is_array($e->getResult()->getVariables())) {
                    $vars = $e->getResult()->getVariables();
                } else {
                    $vars = null;
                }
            } else {
                $vars = $e->getResult();
            }
                       
            /** @var PostProcessor\AbstractPostProcessor $postProcessor */
            $postProcessor = $di->get($formatter . '-pp', array(
                'response' => $e->getResponse(),
                'vars' => $vars,
            ));

            $postProcessor->process();

            return $postProcessor->getResponse();
        }

        return null;
    }

    /**
     * @param MvcEvent $e
     * @return null|Response
     */
    public function errorProcess(MvcEvent $e)
    {
        /** @var \Zend\Di\Di $di */
        $di = $e->getApplication()->getServiceManager()->get('di');

        $eventParams = $e->getParams();
        /** @var array $configuration */
        $config = $e->getApplication()->getConfig();
                
        $vars = array();
        if (isset($eventParams['exception'])) {
            /** @var \Exception $exception */
            $exception = $eventParams['exception'];
            
            $displayErrors = $config['php']['settings']['display_errors'];            
            if ($displayErrors == true) {
                if ($config['errors']['show_exceptions']['message']) {
                    $vars['status'] = -1;
                    $vars['message'] = $exception->getMessage();
                }
                if ($config['errors']['show_exceptions']['trace']) {
                    $vars['trace'] = $exception->__toString();
                } 
            } else {     
                $log = new \Util\Model\Service\ErrorService();                                       
                if ($config['error']['local_log']) {                    
                    $log->logException($exception);           
                }                
                if ($config['error']['send_mail']) {
                    try {
                        \Util\Common\Email::reportException($exception);                    
                    } catch (\Exception $ex) {
                        $log->logException($ex);   
                    }
                }
                
                $vars['status']['code'] = BaseResponse::RESPONSE_STATUS_ERROR_CODE;
                $vars['status']['dateTime'] = date('Y-m-d H:i:s');
                $vars['error']['code'] = BaseResponse::STATUS_CODE_500;
                $vars['error']['message'] = BaseResponse::ERROR_MESSAGE_500;                                
            }
        }

        if (empty($vars)) {
            $vars['status']['code'] = BaseResponse::RESPONSE_STATUS_ERROR_CODE;
            $vars['status']['dateTime'] = date('Y-m-d H:i:s');
            $vars['error']['code'] = BaseResponse::STATUS_CODE_501;
            $vars['error']['message'] = BaseResponse::ERROR_MESSAGE_500;
        }

        /** @var PostProcessor\AbstractPostProcessor $postProcessor */
        $postProcessor = $di->get(
                $config['errors']['post_processor'], array('vars' => $vars, 'response' => $e->getResponse())
        );

        $postProcessor->process();

        if (
            $eventParams['error'] === Application::ERROR_CONTROLLER_NOT_FOUND ||
            $eventParams['error'] === Application::ERROR_ROUTER_NO_MATCH
        ) {
            $e->getResponse()->setStatusCode(Response::STATUS_CODE_501);
        } else {
            $e->getResponse()->setStatusCode(Response::STATUS_CODE_500);
        }

        $e->stopPropagation();

        return $postProcessor->getResponse();
    }
}
