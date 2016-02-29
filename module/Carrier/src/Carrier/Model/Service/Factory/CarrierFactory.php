<?php

namespace Carrier\Model\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\FactoryInterface;
use Carrier\Model\Service\CarrierService;
use Carrier\Model\Repository\CarrierRepository;

class CarrierFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {        
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $repository = new CarrierRepository($adapter);
        
        $cache = $serviceLocator->get('cache'); 
        $repository->setCache($cache);
        
        return new CarrierService($repository);
    }
}