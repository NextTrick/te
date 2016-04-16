<?php
namespace Track\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class TrackRepository extends AbstractRepository
{
    protected $_table = 'fcb_track_track';
    
    protected $_id = 'trackId';
    
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
}