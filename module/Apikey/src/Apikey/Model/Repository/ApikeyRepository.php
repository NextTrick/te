<?php
namespace Apikey\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class ApikeyRepository extends AbstractRepository
{
    protected $_table = 'fcb_service_apikey';
    
    protected $_id = 'apikeyId';
}