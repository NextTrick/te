<?php
return array(
    'php' => array(
        'settings' =>
            array(
                'date.timezone' => 'America/Los_Angeles',
                'intl.default_locale' => 'es_PE',
                'display_startup_errors' => true,
                'display_errors' => true,
                'error_reporting' => E_ALL,
                'post_max_size' => '804857600',  
            )
    ),    
    
    'error' => array(
        'send_mail' => true,
        'local_log' => true,        
    ),
    
    'cache' => array(
        'adapter' => array(
            'name' => 'filesystem',
            'options' => array(
                'dirLevel' => 1,
                'cacheDir' => 'data/history/adyen',
                'dirPermission' => 0755,
                'filePermission' => 0666,
                'namespaceSeparator' => '-bl-',
                'ttl' => 60*60*24,
            ),
        ),
        'plugins' => array('serializer')
    ),
    
    'view_manager' => array(
        'base_path' => "http://trackingengine.bongous.com/",
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
    
    // Db paráms    
    'db' => array(
        //this is for primary adapter....
        'username' => 'angel.jara',
        'password' => '2650953180',
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=bongo;host=10.50.1.8',
        'profiler' => true,
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
        'adapters' => array(
            'db1' => array(
                'username' => 'root',
                'password' => '',
            ),
            'db2' => array(
                'username' => 'other_user',
                'password' => 'other_user_passwd',
            ),
        ),
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
            'host' => 'http://listener.bongous.com/',
            'version' => '?v1.1'
        ),
        'element' => array(
            'host' => 'http://listener.bongous.com/',                
        ),
        'content' => array(
            'host' => 'http://listener.bongous.com/',                
        ),            
    ), 
    
    'service_manager' => array(
        'factories' => array(
            //'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'DbAdapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ),
    )
);