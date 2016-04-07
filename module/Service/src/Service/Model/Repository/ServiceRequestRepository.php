<?php
namespace Service\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class ServiceRequestRepository extends AbstractRepository
{
    protected $_table = 'fcb_service_request';
    
    protected $_id = 'requestId';
   
}