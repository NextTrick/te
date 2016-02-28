<?php

namespace Util\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Server extends AbstractPlugin
{
    private $_serversConfig;
   
    public function __construct($serversConfig) 
    {
        $this->_serversConfig = $serversConfig;        
    }

    public function __invoke($serverKey, $path) 
    {
        if (!empty($this->_serversConfig[$serverKey]['host'])) {
            $host = $this->_serversConfig[$serverKey]['host'] . ltrim($path, '/');
            if (!empty($this->_serversConfig[$serverKey]['version'])) {
                $host .= $this->_serversConfig[$serverKey]['version'];
            }
        }
        
        return $host;
    }
}