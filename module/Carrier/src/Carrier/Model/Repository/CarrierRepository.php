<?php
namespace Carrier\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class CarrierRepository extends AbstractRepository
{
    protected $_table = 'fcb_carrier_carrier';
    
    protected $_id = 'carrierId';
    
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    
    public function getByStatus($status = 1)
    {
        $where = array(
            'status' => $status
        );
        
        return $this->getBy($where);
    }
}