<?php
namespace Carrier;

return array(
    'factories' => array(
        'Model\CarrierService' => 'Carrier\Model\Service\Factory\CarrierFactory',
        'Model\RequestService' => 'Carrier\Model\Service\Factory\RequestFactory',       
    ),
    'invokables' => array(        
    ),
);