<?php
namespace Service\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class MultiTrackingRepository extends AbstractRepository
{
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;

    protected $_table = 'fcb_service_multitracking';
    
    protected $_id = 'multitrackingId';
    
}