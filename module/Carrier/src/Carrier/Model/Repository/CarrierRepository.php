<?php
namespace Carrier\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class CarrierRepository extends AbstractRepository
{
    protected $_table = 'fcb_carrier_carrier';
    
    protected $_id = 'carrierId';
}