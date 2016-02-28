<?php
namespace Search\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class TrackingRepository extends AbstractRepository
{
    protected $_table = 'fcb_search_tracking';
    
    protected $_id = 'tracking_id';
}