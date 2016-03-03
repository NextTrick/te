<?php

namespace Statistic\Model\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\FactoryInterface;
use Statistic\Model\Service\ServiceApikeyService;
use Statistic\Model\Repository\ServiceApikeyRepository;

class ServiceApikeyFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $repository = new ServiceApikeyRepository($adapter);
        
        $cache = $serviceLocator->get('cache'); 
        $repository->setCache($cache);
        
        return new ServiceApikeyService($repository);
    }
}