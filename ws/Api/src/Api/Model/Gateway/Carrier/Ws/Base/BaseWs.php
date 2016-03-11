<?php

namespace Api\Model\Gateway\Carrier\Ws\Base;

class BaseWs 
{       
    const ERROR_GENERIC_CODE = 500;
    
    const ERROR_GENERIC_MESSAGE = 'Failed Ws connection'; 
    
    public $client;

    public function getClient()
    {
        return $this->client;
    }
}
