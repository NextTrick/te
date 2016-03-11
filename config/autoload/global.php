<?php
return array(
    'php' => array(
        'settings' =>
            array(
                'date.timezone' => 'America/Los_Angeles',
                'intl.default_locale' => 'es_PE',                
                'display_startup_errors' => true,
                'display_errors' => false, // if = false, display_startup_errors and  display_startup_errors not important
                'error_reporting' => E_ALL,
                'display_startup_errors' => true,
                'post_max_size' => '804857600',  
            )
    ),    
    
    'error' => array(
        'send_mail' => false,
        'local_log' => false,        
    ),
    
    'cache' => array(
        'adapter' => array(
            'name' => 'filesystem',
            'options' => array(
                'dirLevel' => 1,
                'cacheDir' => 'data/cache',
                'dirPermission' => 0755,
                'filePermission' => 0666,
                'namespaceSeparator' => '-bl-',
                'ttl' => 60*60*24,
            ),
        ),
        'plugins' => array('serializer')
    ),
    
    'view_manager' => array(
        'base_path' => "http://trackingengine.bongous.dev/",
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'charset' => 'UTF-8',
        'doctype' => 'HTML5',
        'title' => 'Bongo Listener',
        'strategies' => array(
           'ViewJsonStrategy',
        ),
    ),
    //Parámetros de la applicación
    'app' => array(                
    ),
    
    'mail' => array(
        'transport' => array(
            'options' => array(
                'host' => 'smtp.1and1.com',
                'port' => 25,
                'connection_class'  => 'login',
                'connection_config' => array(
                    'username' => 'logs@mayopi.com',
                    'password' => 'EP6vQdL3',
                    'ssl' => 'tls',
                ),
            ),
        ),
        'fromEmail' => 'support@bongous.com',
        'fromName' => 'BongoUs',
        'subject' => 'Custom Subject'
    ),
                
    //Emails
    'emails' => array(
        'admin' => 'ing.angeljara@gmail.com', // email del administrador
        'developers' => 'angel.jara@bongous.com', // emails de los dev
        'from' => 'contacto@bongous.com',
    ), 
    
    //Servers
    'servers' => array(
        'static' => array(
            'host' => 'http://trackingengine.bongous.dev/',
            'version' => '?v1.1'
        ),
        'element' => array(
            'host' => 'http://trackingengine.bongous.dev/',                
        ),
        'content' => array(
            'host' => 'http://trackingengine.bongous.dev/',                
        ),            
    ), 
    
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ),
    ),
    'carrier' => array(
        'trackLifeTime' => 5, // minutes
        'Dhl' => array(
            'tracking' => array(
                'siteId' => '846176581',
                'password' => '5A233F2B2C415144445955595D53304F424A5C414D5951545C54',
                'host' => 'https://xmlpi-ea.dhl.com/',
            )
        ),
        'FedexCrossBorder' => array(
            'tracking' => array(                
            )
        ),
        'Fedex' => array(
            'tracking' => array(
                'key' => 'fKVz5JPu347lelYU',
                'password' => 'SubRKBdOrU3qQZDm8CwFl9sMZ',
                'accountNumber' => '510087968',
                'meterNumber' => '118707157',
            ),
        ),
        'Ups' => array(
            'tracking' => array(              
                'userName' => 'xxxx',
                'password' => 'xxx',
                'serviceAccessToken' => 'xxxx',
                'url' => 'https://wwwcie.ups.com/webservices/Track', 
                //'endpoint' => 'https://onlinetools.ups.com/webservices/Track', // PRODUCTION
            ),
        ),
        'Usps' => array(
            'tracking' => array(              
                'userId' => '916BONGO5774',  
                'password' => '740DP32QR984',                  
                'url' => 'http://stg-production.shippingapis.com/ShippingAPI.dll?API=TrackV2', 
                //'url' => 'http://production.shippingapis.com/ShippingAPI.dll?API=TrackV2', , // PRODUCTION
            ),
        ),
    ),
);