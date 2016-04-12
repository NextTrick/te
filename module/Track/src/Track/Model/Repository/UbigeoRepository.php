<?php
namespace Track\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class UbigeoRepository extends AbstractRepository
{
    protected $_table = 'fcb_track_ubigeo';
    
    protected $_id = 'ubigeoId';
    
    public function getByStateOrProvinceCodeCountryCode($stateOrProvinceCode, $countryCode)
    {
        $where = array(
            'stateOrProvinceCode' => $stateOrProvinceCode,
            'countryCode' => $countryCode,
        );
        
        return $this->getBy($where, true);
    }
}