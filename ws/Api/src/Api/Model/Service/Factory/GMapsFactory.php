<?php

namespace Api\Model\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\FactoryInterface;
use Api\Model\Service\GMapsService;

class GMapsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {    
        $config = $serviceLocator->get('config');
        return new GMapsService($serviceLocator, $config['googleApi']['key']);
    }
}