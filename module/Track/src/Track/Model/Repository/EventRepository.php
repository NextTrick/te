<?php
namespace Track\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class EventRepository extends AbstractRepository
{
    protected $_table = 'fcb_track_event';
    
    protected $_id = 'eventId';
}