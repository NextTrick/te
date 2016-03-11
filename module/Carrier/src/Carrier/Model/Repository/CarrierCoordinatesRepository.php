<?php
namespace Carrier\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class CarrierCoordinatesRepository extends AbstractRepository
{
    protected $_table = 'fcb_carrier_coordinates';
    
    protected $_id = 'coordinateId';
    
    public function getByCode($code)
    {
        $where = array(
            'code=?' => $code
        );
        return $this->getBy($where, TRUE);
    }
    
    public function inser()
    {
        
    }
}