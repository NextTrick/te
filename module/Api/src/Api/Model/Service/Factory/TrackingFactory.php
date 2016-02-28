<?php

namespace Api\Model\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\FactoryInterface;
use Api\Model\Service\TrackingService;

class TrackingFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {               
        return new TrackingService($serviceLocator);
    }
}