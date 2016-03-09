<?php
namespace Apikey\Model\Service;

use Util\Model\Service\Base\AbstractService;

class ApikeyService extends AbstractService
{
    public function getApikeyIdByKeyProfileId($key, $profileId)
    {
        $apikeyData = $this->getRepository()
                ->getByKeyProfileId($key, $profileId); 
        
        if (empty($apikeyData)) {
            $apikeyData = array(
                'key' => $key,
                'profileId' => $profileId,
            );
            
            $apikeyId = $this->getRepository()->save($apikeyData);            
        } else {
            $apikeyId = $apikeyData['apikeyId'];
        }
        
        return $apikeyId;
    }
}