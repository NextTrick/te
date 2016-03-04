<?php
namespace Carrier\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class CarrierRepository extends AbstractRepository
{
    protected $_table = 'fcb_carrier_carrier';
    
    protected $_id = 'carrierId';
    
    const STATUS_ACTIVE = 1;    
    const STATUS_INACTIVE = 0;
    
    const FEDEX_ID = 1;
    const DHL_ID = 2;
    
    public function getByStatus($status = 1)
    {
        /*return array(
           array("carrierId"=>"2",
                "name"=>"Dhl",
                "status"=>"1",
                "alias"=>"Dhl")
            );*/
        $where = array(
            'status' => $status
        );
        return $this->getBy($where);
    }
}