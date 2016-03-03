<?php
namespace Search\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;
use Zend\Db\Sql\Predicate\Expression;

class TrackRepository extends AbstractRepository
{
    protected $_table = 'fcb_search_track';
    
    protected $_id = 'tracking_id';
    
    public function getLastValidTrack($trackingKey, $carrierId, $trackLifeTime)
    {        
        $cDate = date('Y-m-d H:i:s');
        $select = $this->sql->select(array('a' => $this->_table));
        $select->where->equalTo('a.trackingKey', $trackingKey);
        $select->where->equalTo('a.carrierId', $carrierId);
        $select->where(new Expression("TIMESTAMPDIFF(MINUTE, a.creationDate, '{$cDate}') < $trackLifeTime"));
                
        return $this->fetchRow($select);
    }
}