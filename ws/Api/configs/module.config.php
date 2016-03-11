<?php

return array(
    'errors' => array(
        'post_processor' => 'json-pp',
        'show_exceptions' => array(
            'message' => true,
            'trace' => true
        )
    ),
    'di' => array(
        'instance' => array(
            'alias' => array(
                'json-pp' => 'Api\PostProcessor\Json',
                'image-pp' => 'Api\PostProcessor\Image',
                'xml-pp' => 'Api\PostProcessor\Xml',
                'phps-pp' => 'Api\PostProcessor\Phps',
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'tracking' => 'Api\Controller\TrackingController',
            'unified-tracking' => 'Api\Controller\UnifiedTrackingController',
            'multitracking' => 'Api\Controller\MultitrackingController',
        )
    ),
    'router' => array(
        'routes' => array(
            'restful' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/:controller[/:id][/:key]',
                    'constraints' => array(
                        '__NAMESPACE__' => 'Api\Controller',                        
                        'module'        => 'Api',
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'formatter' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*'
                    ),
                ),
            ),
        ),
    ),
);
