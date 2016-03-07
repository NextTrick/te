<?php
namespace Service\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class MultiTrackingRepository extends AbstractRepository
{
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;

    protected $_table = 'fcb_service_multitracking';
    
    protected $_id = 'multitrackingId';
    
    public function getByIdStatus($token, $status) 
    {
        return $this->getBy(
                array(
                    'token=?' => $token, 
                    'status=?' => $status
                ), 
                TRUE
            );
    }
    
    /**
     * 
     * @param type $params
     * @param type $token
     */
    public function updateMultiTrackingByToken($params, $token)
    {
        $response = array('success' => true);
        try {
             $this->update(
               $params,
                array(
                    'token=?' => $token
                )
            );
        } catch (Exception $e) {
            $response = array(
                'success' => false , 
                'message' => $e->getMessage()
            );
        }
        return $response;
    }
}