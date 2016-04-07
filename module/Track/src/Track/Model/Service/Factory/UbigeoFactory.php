<?php

namespace Track\Model\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\FactoryInterface;
use Track\Model\Service\UbigeoService;
use Track\Model\Repository\UbigeoRepository;

class TrackFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $repository = new UbigeoRepository($adapter);
        
        $cache = $serviceLocator->get('cache'); 
        $repository->setCache($cache);
        
        return new UbigeoService($repository);
    }
}