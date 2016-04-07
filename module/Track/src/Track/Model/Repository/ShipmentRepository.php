<?php
namespace Track\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class ShipmentRepository extends AbstractRepository
{
    protected $_table = 'fcb_track_shipment';
    
    protected $_id = 'shipmentId';
}