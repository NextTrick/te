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
}