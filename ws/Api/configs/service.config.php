<?php

return array(
    'factories' => array(
        'Api\Model\TrackingService' => 'Api\Model\Service\Factory\TrackingFactory',  
        'Api\Model\GMapsService' => 'Api\Model\Service\Factory\GMapsFactory',  
    ),
    'invokables' => array(
        
    ),
);