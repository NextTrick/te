<?php
namespace Track\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class TrackRepository extends AbstractRepository
{
    protected $_table = 'fcb_track_track';
    
    protected $_id = 'trackId';
}