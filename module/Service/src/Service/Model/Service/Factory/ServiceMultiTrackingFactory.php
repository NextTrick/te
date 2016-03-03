<?php

namespace Service\Model\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\FactoryInterface;
use Service\Model\Service\ServiceMultiTrackingServiceService;
use Service\Model\Repository\ServiceMultiTrackingRepository;

class ServiceMultiTrackingFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $repository = new ServiceMultiTrackingRepository($adapter);
        
        $cache = $serviceLocator->get('cache'); 
        $repository->setCache($cache);
        
        return new ServiceMultiTrackingServiceService($repository);
    }
}