<?php

namespace Carrier\Model\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\FactoryInterface;
use Carrier\Model\Service\CarrierCoordinatesService;
use Carrier\Model\Repository\CarrierCoordinatesRepository;

class CarrierCoordinatesFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {        
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $repository = new CarrierCoordinatesRepository($adapter);
        
        $cache = $serviceLocator->get('cache'); 
        $repository->setCache($cache);
        
        return new CarrierCoordinatesService($repository);
    }
}