<?php

namespace Track\Model\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\FactoryInterface;
use Track\Model\Service\EventStatusService;
use Track\Model\Repository\EventStatusRepository;

class EventStatusFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $repository = new EventStatusRepository($adapter);
        
        $cache = $serviceLocator->get('cache'); 
        $repository->setCache($cache);
        
        return new EventStatusService($repository);
    }
}