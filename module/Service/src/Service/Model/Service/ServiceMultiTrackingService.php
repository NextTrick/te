<?php
namespace Service\Model\Service;

use Util\Model\Service\Base\AbstractService;
use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
class ServiceMultiTrackingService extends AbstractService
{
    const ERROR_CODE = 555;
    
    const ERROR_MESSAGE = 'Parametros de entrada no validos';
    
    public function insertMultiTracking($tractings = array())
    {
        $response = array(
             'status' => array(
                 'code' => CarrierAbstract::RESPONSE_STATUS_SUCCESS_CODE,
                 'dateTime' => '',
             ),
         );
       $responseValidation = $this->getValidation($tractings);
       if(!empty($responseValidation)) {
           $response['status']['code'] = CarrierAbstract::RESPONSE_STATUS_ERROR_CODE;
           $response['error']['code'] = self::ERROR_CODE;
           $response['error']['message'] = self::ERROR_MESSAGE;
           $response['error']['errors'] = $responseValidation;
       }
       var_dump($response);exit;
       if($response['status']['code'] = CarrierAbstract::RESPONSE_STATUS_SUCCESS_CODE){
           $this->getRepository()->insertMultiTracking($tractings);
       }
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
}