<?php
namespace Service\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class ServiceMultiTrackingRepository extends AbstractRepository
{
    protected $_table = 'fcb_service_multitracking';
    
    protected $_id = 'multitrackingId';
    
    public function insertMultiTracking($tractings = array())
    {
        $response = array('success' => true);
        if(empty($tractings)){
            return array(
                'success' => false,
                'messages' => 'Tracking no validos',
            );
        }
        try {
            foreach ($tractings as $tracking) {
                $this->insert($tracking);
            }
        } catch (\Exception $e) {
            $response = array(
                'success' => false,
                'messages' => $e->getMessage(),
            );
        }
        return $response;
    }
}