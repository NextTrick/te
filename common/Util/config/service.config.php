<?php
namespace Util;

use Zend\Db\Adapter\Adapter;
use Zend\Cache\StorageFactory;

return array(
//    'factories' => array(
//        'dbAdapter' => function($sm) {
//            $config = $sm->get('config');
//            $config = $config['db'];
//            $dbAdapter = new Adapter($config);
//            return $dbAdapter;
//        },            
//        'cache' => function($sm) {
//            $cache = StorageFactory::factory(array(
//                    'adapter' => 'filesystem',
//                    'plugins' => array(
//                        'exception_handler' => array('throw_exceptions' => false),
//                        'serializer'
//                    )
//            ));
//            $cache->setOptions(array(
//                'cache_dir' => './data/cache',
//                'readable' => true,
//                'ttl' => 3600
//            ));
//
//            return $cache;
//        },
//    ),
//    'invokables' => array(
//    ),
);
