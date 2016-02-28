<?php

namespace Auth\Controller\Plugin\Factory;

use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\FactoryInterface;
use Auth\Controller\Plugin\Auth;

class AuthFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {                
        $authenticationService = $serviceLocator->get('Auth\Model\Service\AuthenticationService');
        
        return new Auth($authenticationService);
    }
}