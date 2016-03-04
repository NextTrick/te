<?php
namespace Service\Model\Service;

use Util\Model\Service\Base\AbstractService;
use Api\Model\Gateway\Carrier\Base\CarrierAbstract;

class MultiTrackingService extends AbstractService
{
    const ERROR_CODE_900 = 900;
    const ERROR_MESSAGE_900 = 'Parametros de entrada no validos';
    
    public function insertMultiTracking($tractings = array(), $apiKeyId)
    {
        $response = array(
             'status' => array(
                 'code' => CarrierAbstract::RESPONSE_STATUS_SUCCESS_CODE,
                 'dateTime' => date('Y-m-d H:i:s'),
             ),
         );
       $responseValidation = $this->getValidation($tractings);
       if(!empty($responseValidation)) {
           $response['status']['code'] = CarrierAbstract::RESPONSE_STATUS_ERROR_CODE;
           $response['error']['code'] = self::ERROR_CODE_900;
           $response['error']['message'] = self::ERROR_MESSAGE_900;
           $response['error']['errors'] = $responseValidation;
       }
       if($response['status']['code'] == CarrierAbstract::RESPONSE_STATUS_SUCCESS_CODE){
           $token = sha1(uniqid());
           $this->getRepository()
                ->insert(array(
                    'trackingKeys' => json_encode($tractings['trackings']),
                    'apiKeyId' => $apiKeyId,
                    'creationDate' => date('Y-m-d H:i:s'),
                    'token' => $token
                ));
          $response['data'] = array(
                'token' => $token,
              ); 
       }
       return $response;
    }
    
    public function getValidation($params = array())
    {
        if(empty($params['trackings'])) {
            $response[] = array(
                'code' => 2,
                'message' => 'Trackings no enviados',
            );
        }
        return $response;
    }
}