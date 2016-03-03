<?php
namespace Statistic\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class ServiceApikeyRepository extends AbstractRepository
{
    protected $_table = 'fcb_statistic_service_apikey';
    
    protected $_id = 'serviceApikeyId';
    
    public function getByServiceIdAndApikeyId($serviceId, $apikeyId)
    {
        $where = array(
            'serviceId' => $serviceId,
            'apikeyId' => $apikeyId,
        );
        
        return $this->getBy($where, true);
    }
}