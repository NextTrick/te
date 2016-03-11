<?php
namespace Carrier\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class RequestRepository extends AbstractRepository
{
    protected $_table = 'fcb_carrier_request';
    
    protected $_id = 'requestId';
}