<?php

namespace Search\Model\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\FactoryInterface;
use Search\Model\Service\TrackService;
use Search\Model\Repository\TrackRepository;

class TrackFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $repository = new TrackRepository($adapter);
        
        $cache = $serviceLocator->get('cache'); 
        $repository->setCache($cache);
        
        return new TrackService($repository);
    }
}