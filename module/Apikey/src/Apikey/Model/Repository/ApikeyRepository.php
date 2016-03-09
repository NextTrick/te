<?php
namespace Apikey\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class ApikeyRepository extends AbstractRepository
{
    protected $_table = 'fcb_apikey_apikey';
    
    protected $_id = 'apikeyId';
    
    public function getByKey($key)
    {
        $where = array(
            'key' => $key
        );
        
        return $this->getBy($where, true);
    }
    
    public function getByKeyServiceId($key, $serviceId)
    {
        $select = $this->sql->select(array('a' => $this->_table));
        $select->join(array('b' => 'fcb_statistic_service_apikey'), 'a.apikeyId = b.apikeyId', array('*'))
              ->join(array('c' => 'fcb_service_service'), 'b.serviceId = c.serviceId', array());
        $select->where->equalTo('a.key', $key);
        $select->where->equalTo('c.serviceId', $serviceId);
        
        return $this->fetchRow($select);
    }
    
    public function getByKeyProfileId($key, $profileId)
    {
        $where = array(
            'key' => $key,
            'profileId' => $profileId,
        );
        
        return $this->getBy($where, true);
    }
}