<?php
return array(
    'router' => array(
        'routes' => array(                        
            'test' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/test[/:controller[/:action]]',                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'Test\Controller',                        
                        '__CONTROLLER__'=> 'index',
                        'module'        => 'Test',
                        'controller'    => 'index',
                        'action'        => 'index',
                    ),                    
                ),
                'may_terminate' => true,
                'child_routes' => array(                    
                    'default' => array(
                        'type' => 'Wildcard',
                        'options' => array(                            
                        ),
                    ),
                ),
            ),
            
        ),
    ),        
    'view_manager' => array(
        'template_path_stack' => array(
            'Payment' => __DIR__ . '/../view',
        ),
    ),    
    'controllers' => array(
        'invokables' => array(
            'Test\Controller\Index' => 'Test\Controller\IndexController',        
        )
    ),    
);