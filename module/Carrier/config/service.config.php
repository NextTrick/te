<?php
namespace Carrier;

return array(
    'factories' => array(
        'Model\CarrierService' => 'Carrier\Model\Service\Factory\CarrierFactory',
        'Model\RequestService' => 'Carrier\Model\Service\Factory\RequestFactory', 
        'Model\CarrierCoordinatesService' => 'Carrier\Model\Service\Factory\CarrierCoordinatesFactory', 
        
    ),
    'invokables' => array(        
    ),
);