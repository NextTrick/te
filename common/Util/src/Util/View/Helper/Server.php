<?php

namespace Util\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Server extends AbstractHelper
{
    private $_serversConfig;
   
    public function __construct($serversConfig) 
    {
        $this->_serversConfig = $serversConfig;        
    }

    public function __invoke($serverKey, $path = '', $version = true) 
    {
        $host = '';
        if (!empty($this->_serversConfig[$serverKey]['host'])) {
            $host = rtrim($this->_serversConfig[$serverKey]['host'], '/') . '/';            
            if (!empty($path)) {
                $host .= ltrim($path, '/') . '/';
                if (!empty($this->_serversConfig[$serverKey]['version']) && $version == true) {
                    $host = rtrim($host, '/');
                    $host .= '?' . ltrim($this->_serversConfig[$serverKey]['version'], '?');
                }
            }
        }
        
        return $host;
    }
}
