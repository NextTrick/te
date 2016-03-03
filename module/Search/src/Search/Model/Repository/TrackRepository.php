<?php
namespace Search\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class TrackRepository extends AbstractRepository
{
    protected $_table = 'fcb_search_track';
    
    protected $_id = 'tracking_id';
    
    public function getLastValidTrack($trackingKey, $partnerId, $trackLifeTime)
    {        
        $cDate = date('Y-m-d H:i:s');
        $select = $this->sql->select(array('a' => $this->_table));
        $select->where->equalTo('a.trackingKey', $trackingKey);
        $select->where->equalTo('a.partnerId', $partnerId);
        $select->where(new Expression("TIMESTAMPDIFF(MINUTE, a.creationDate, '{$cDate}') < $trackLifeTime"));
        
        return $this->fetchRow($select);
    }
}