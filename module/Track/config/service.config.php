<?php
namespace Service;

return array(
    'factories' => array(
        'Model\TrackService' => 'Track\Model\Service\Factory\TrackFactory',
        'Model\ShipmentService' => 'Track\Model\Service\Factory\ShipmentFactory',
        'Model\EventService' => 'Track\Model\Service\Factory\EventFactory',
        'Model\EventStatusService' => 'Track\Model\Service\Factory\EventStatusFactory',
        'Model\UbigeoService' => 'Track\Model\Service\Factory\UbigeFactory',
    ),
    'invokables' => array(        
    ),
);