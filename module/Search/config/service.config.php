<?php
namespace Search;

return array(
    'factories' => array(
        'Model\SearchService' => 'Search\Model\Service\Factory\SearchFactory',
        'Model\TrackService' => 'Search\Model\Service\Factory\TrackFactory',       
    ),
    'invokables' => array(        
    ),
);