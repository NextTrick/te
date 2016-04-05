<?php
namespace Service\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class ServiceRepository extends AbstractRepository
{
    protected $_table = 'fcb_service_service';
    
    protected $_id = 'serviceId';
    
    const ENDPOINT_TRACKING_ID = 1;
    const ENDPOINT_MULTITRACKING_ID = 2;
    const ENDPOINT_UNIFIED_TRACKING_ID = 3;
}