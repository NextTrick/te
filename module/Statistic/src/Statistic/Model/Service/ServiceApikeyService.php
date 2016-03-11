<?php
namespace Statistic\Model\Service;

use Util\Model\Service\Base\AbstractService;

class ServiceApikeyService extends AbstractService
{
    public function save($data)
    {
        $dbData = $this->getRepository()
                ->getByServiceIdAndApikeyId($data['serviceId'], $data['apikeyId']);
        
        if (empty($dbData)) {
            $dbData = array(
                'serviceId' => $data['serviceId'],
                'apikeyId' => $data['apikeyId'],
                'counter' => 1,
            );                                    
        } else {
            $dbData['counter'] = $dbData['counter'] + 1;            
        }
        
        return $this->getRepository()->save($dbData);
    }
}