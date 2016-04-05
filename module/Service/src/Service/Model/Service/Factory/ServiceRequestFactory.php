<?php

namespace Service\Model\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\FactoryInterface;
use Service\Model\Service\ServiceService;
use Service\Model\Repository\ServiceRepository;

class ServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $repository = new ServiceRepository($adapter);
        
        $cache = $serviceLocator->get('cache'); 
        $repository->setCache($cache);
        
        return new ServiceService($repository);
    }
}