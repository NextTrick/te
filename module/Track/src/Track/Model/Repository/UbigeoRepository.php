<?php
namespace Track\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class UbigeoRepository extends AbstractRepository
{
    protected $_table = 'fcb_track_ubigeo';
    
    protected $_id = 'ubigeoId';
    
    public function getByStateOrProvinceCodeCountryCode($stateOrProvinceCode, $countryCode)
    {      
        $select = $this->sql->select($this->_table);
        $select->where
                ->nest()
                ->equalTo('stateOrProvinceCode', $stateOrProvinceCode)
                ->or
                ->equalTo('areaCode', $stateOrProvinceCode)
                ->unnest()
                ->equalTo('countryCode', $countryCode);
        
        return $this->fetchRow($select);
    }
}