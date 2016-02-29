<?php
namespace Util;

use Zend\Db\Adapter\Adapter;
use Zend\Cache\StorageFactory;

return array(
    'factories' => array(      
        'cache' => function($sm) {
            $config = $sm->get('config');            
            $cache = StorageFactory::factory($config['cache']);
            return $cache;
        },
    ),
    'invokables' => array(
    ),
);
