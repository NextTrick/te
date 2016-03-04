<?php
namespace Service\Model\Service;

use Util\Model\Service\Base\AbstractService;
use Api\Model\Gateway\Carrier\Base\CarrierAbstract;
use Api\Controller\Base\BaseResponse;

class MultiTrackingService extends AbstractService
{
    const ERROR_CODE = 555;
    const ERROR_MESSAGE = 'Parametros de entrada no validos';
    
    public function insertMultiTracking($tractings = array())
    {
        $response = array(
             'status' => array(
                 'code' => BaseResponse::RESPONSE_STATUS_SUCCESS_CODE,
                 'dateTime' => '',
             ),
         );
       $responseValidation = $this->getValidation($tractings);
       if(!empty($responseValidation)) {
           $response['status']['code'] = BaseResponse::RESPONSE_STATUS_ERROR_CODE;
           $response['error']['code'] = self::ERROR_CODE;
           $response['error']['message'] = self::ERROR_MESSAGE;
           $response['error']['errors'] = $responseValidation;
       }
       if($response['status']['code'] == BaseResponse::RESPONSE_STATUS_SUCCESS_CODE){
           $idMultiTracking = $this->getRepository()
                   ->insert(array(
                       'trackingIds' => json_encode($tractings['trackings']),
                       'apiKeyId' => '',
                       'creationDate' => date('Y-m-d H:i:s'),
                       'token' => sha1()
                   ));
          $response['data'] = array(
                'idMultiTracking' => $idMultiTracking,
              ); 
       }
       return $response;
    }
    
    public function getValidation($params = array())
    {
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