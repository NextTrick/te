<?php
namespace Service;

return array(
    'factories' => array(
        'Model\MultiTrackingService' => 'Service\Model\Service\Factory\MultiTrackingFactory',    
        'Model\ServiceRequestService' => 'Service\Model\Service\Factory\ServiceRequestFactory',    
    ),
    'invokables' => array(        
    ),
);