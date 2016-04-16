<?php
namespace Track\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class EventRepository extends AbstractRepository
{
    protected $_table = 'fcb_track_event';
    
    protected $_id = 'eventId';
    
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    
    public function getByTrackId($trackId)
    {
        $where = array(
            'trackId' => $trackId
        );
        
        return $this->getBy($where, true);
    }
}