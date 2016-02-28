<?php

return array(
    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'api_route_aba' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/api',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Api\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en',
        'translation_file_patterns' => array(
            array(
                'type' => 'phparray',
                'base_dir' => dirname(__DIR__) . '/language/checkout',
                'pattern' => '%s.php',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Api\Controller\Security' => 'Api\Controller\SecurityController',
            'Api\Controller\Sync' => 'Api\Controller\SyncController',    
            'Api\Controller\Bpabandon' => 'Api\Controller\BpabandonController',            
            'Api\Controller\Connectlanded' => 'Api\Controller\ConnectlandedController',
            'Api\Controller\Products' => 'Api\Controller\ProductsController',
            'Api\Controller\PaymentAuthorization' => 'Api\Controller\PaymentAuthorizationController',
            'Api\Controller\PaymentCapture' => 'Api\Controller\PaymentCaptureController',
            'Api\Controller\PaymentRefund' => 'Api\Controller\PaymentRefundController',
            'Api\Controller\PaymentAuthorizationCapture' => 'Api\Controller\PaymentAuthorizationCaptureController',
            'Api\Controller\PaymentAuthorizationReversal' => 'Api\Controller\PaymentAuthorizationReversalController',
        ),
    ),
    'view_manager' => array(        
        'strategies' => array(
            'ViewJsonStrategy',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
                'send_order_abandon' => array(
                    'options' => array(
                        'route'    => 'send_order_abandon',
                        'defaults' => array(
                            'controller' => 'Api\Controller\Bpabandon',
                            'action' => 'send-abandon'
                        )
                    )
                ),                
                'send_connect' => array(
                    'options' => array(
                        'route'    => 'send_connect',
                        'defaults' => array(
                            'controller' => 'Api\Controller\ConnectlandedController',
                            'action' => 'send-connect'
                        )
                    )
                )
            )
        )
    ),    
);
