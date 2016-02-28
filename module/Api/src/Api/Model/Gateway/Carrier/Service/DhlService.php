<?php

namespace Api\Model\Gateway\Carrier\Service;

use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
class DhlService extends CarrierAbstract
{
    public function __construnct()
    {
        
    }
    
    public function getTracking() {
        parent::getTracking();
    }

    public function getMultitracking()
    {
        
    }
}

