<?php
namespace Service\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class ServiceRepository extends AbstractRepository
{
    protected $_table = 'fcb_service_service';
    
    protected $_id = 'serviceId';
}