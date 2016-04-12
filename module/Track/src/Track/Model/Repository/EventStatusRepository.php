<?php
namespace Track\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class EventStatusRepository extends AbstractRepository
{
    protected $_table = 'fcb_track_event_status';
    
    protected $_id = 'eventStatusId';
    
    public function getByCode($eventCode)
    {
        $where = array(
            'eventCode' => $eventCode,            
        );
        
        return $this->getBy($where, true);
    }
}