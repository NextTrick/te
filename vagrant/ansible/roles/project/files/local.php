<?php
return array(
    'php' => array(
        'settings' =>
            array(
                'date.timezone' => 'America/Los_Angeles',
                'intl.default_locale' => 'es_PE',
                'display_startup_errors' => true,
                'display_errors' => false,
                'error_reporting' => E_ALL,
                'post_max_size' => '804857600',  
            )
    ),    
    
    'error' => array(
        'send_mail' => true,
        'local_log' => true,        
    ),
    
    'view_manager' => array(
        'base_path' => "http://trackingegine.bongous.dev/",
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
        'username' => 'bongodbuser',
        'password' => 'B0ng0',
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=fcb_trackingengine;host=localhost',
        'profiler' => true,
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
        'adapters' => array(
            'accountManagerDb' => array(
                'username' => 'bongodbuser',
                'password' => 'B0ng0',
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=fcb_accountmanager;host=localhost',
                'profiler' => true,
                'driver_options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                ),
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
        'developers' => 'angel.jara@bongous.com, jared.cusi@bongous.com', // emails de los dev
        'from' => 'contacto@anglejara.com',
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
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'username' => 'bongodbuser',
                    'password' => 'B0ng0',
                    'dbname'   => 'fcb_trackingengine',
                )
            )
        )
    ),
);
