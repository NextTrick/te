<?php
namespace Service\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class MultiTrackingRepository extends AbstractRepository
{
    protected $_table = 'fcb_service_multitracking';
    
    protected $_id = 'multitrackingId';
    
}