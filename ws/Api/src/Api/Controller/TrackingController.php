<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Api\Model\Service\TrackingService;

class TrackingController extends AbstractRestfulController
{
    const ERROR_CODE = 555;
    const ERROR_MESSAGE = 'Parametros de entrada no validos';

    public $response = array(
        'status' => array(
            'code' => self::RESPONSE_STATUS_ERROR_CODE,
            'dateTime' => '',
        ),
        'error' => array (
            'code' => '',
            'message' => '',
            'description' => '',
            'errors' => array(
                'code' => '',
                'field' => '',
                'message' => ''
            ),
        ),
    );
    
    public function getList()
    {
        return array();
    }

    public function get($id)
    {
        return array(); 
    }

    public function create($data)
    {
       $responseValidation = $this->getValidation($data);
       if(!empty($responseValidation)) {
           $this->response['error']['code'] = self::ERROR_CODE;
           $this->response['error']['message'] = self::ERROR_MESSAGE;
           $this->response['error']['errors'] = $responseValidation;
       }
       
    }

    public function update($id, $data)
    {

    }

    public function delete($id)
    {

    }
    
    public function getValidation($params = array())
    {
        $response = array();
        if(empty($params['key'])) {
            $response[] = array(
                'code' => 1,
                'field' => '',
                'message' => 'Key no se ha enviado',
            );
        }
        
        if(empty($params['trackings'])) {
            $response[] = array(
                'code' => 2,
                'field' => '',
                'message' => 'Trackings no enviados',
            );
        }
        return $response;
    }

    /**
     * @return TrackingService
     */
    public function getMultiTrackingService()
    {
        return $this->getServiceLocator()->get('Api\Model\MultiTrackingService');
    }
}
