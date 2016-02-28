<?php

return array(
    'factories' => array(
        'Api\Model\Service\TrackingService' => 'Api\Model\Service\Factory\TrackingFactory',
        'TrackingService' => function($sm) {         
            return new Api\Model\Service\TrackingService($sm);  
        },
    ),
    'invokables' => array(
        
    ),
);