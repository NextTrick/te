<?php

namespace Util\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Domain extends AbstractHelper
{
    private $_serversConfig;
   
    public function __construct($serversConfig) 
    {
        $this->_serversConfig = $serversConfig;        
    }

    public function __invoke() 
    {
        $domain = '';
        if (!empty($this->_serversConfig['content']['host'])) {            
            $parsed = parse_url($this->_serversConfig['content']['host']); 
            $domain = $parsed['host']; 
        }
        return $domain;

    }
}