<?php

namespace Api\Model\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\FactoryInterface;
use Api\Model\Service\GMapsService;

class GMapsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {               
        return new GMapsService($serviceLocator);
    }
}