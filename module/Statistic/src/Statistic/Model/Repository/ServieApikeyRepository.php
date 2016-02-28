<?php
namespace Statistic\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class ServieApikeyRepository extends AbstractRepository
{
    protected $_table = 'fcb_statistic_service_apikey';
    
    protected $_id = 'serviceApikeyId';
}