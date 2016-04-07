<?php
namespace Track\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class UbigeoRepository extends AbstractRepository
{
    protected $_table = 'fcb_track_ubigeo';
    
    protected $_id = 'ubigeoId';
}