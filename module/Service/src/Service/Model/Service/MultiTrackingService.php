<?php
namespace Service\Model\Service;

use Util\Model\Service\Base\AbstractService;
use Api\Controller\Base\BaseResponse;
use Service\Model\Repository\MultiTrackingRepository;

class MultiTrackingService extends AbstractService
{
    const ERROR_CODE_900 = 900;
    const ERROR_CODE_901 = 901;
    const ERROR_MESSAGE_900 = 'Parametros de entrada no validos';
    const ERROR_MESSAGE_901 = 'El token no existe';


    public function insertMultiTracking($params)
    {
        $response = array(
             'status' => array(
                 'code' => BaseResponse::RESPONSE_STATUS_SUCCESS_CODE,
                 'dateTime' => date('Y-m-d H:i:s'),

             ),
         );
       $responseValidation = $this->getValidation($params);
       if(!empty($responseValidation)) {
           $response['status']['code'] = BaseResponse::RESPONSE_STATUS_ERROR_CODE;
           $response['error']['code'] = self::ERROR_CODE_900;
           $response['error']['message'] = self::ERROR_MESSAGE_900;
           $response['error']['errors'] = $responseValidation;
       }
       if($response['status']['code'] == BaseResponse::RESPONSE_STATUS_SUCCESS_CODE){
           $token = sha1(uniqid());
           $this->getRepository()
                ->insert(array(
                    'trackingKeys' => json_encode($params['trackings']),
                    'apiKeyId' => $params['apikeyId'],
                    'creationDate' => date('Y-m-d H:i:s'),
                    'token' => $token
                ));
           $dataRequest = array(
                'request' => json_encode($params),
                'requestResponse' => json_encode($response),
                'serviceId'=> $params['serviceId'],
                'apikeyId'=> $params['apikeyId'],
                'requestDate' => date('Y-m-d H:i:s')
            );
            try {
                $this->getServiceRequestService()
                    ->getRepository()
                    ->insert($dataRequest);
            } catch (\Exception $exc) {
                echo $exc->__toString();exit;
            }

            
            $this->getServiceApikeyService()
                ->save(array(
                    'serviceId' => $params['serviceId'],
                    'apikeyId' => $params['apikeyId'],
                ));
           
          $response['data'] = array(
                'token' => $token,
              ); 
       }
       return $response;
    }
    
    /**
     * 
     * @param type $token
     * @param type $params
     * @return type
     */
    public function deleteMultiTracking($token, $params)
    {
        $response = array(
             'status' => array(
                 'code' => BaseResponse::RESPONSE_STATUS_SUCCESS_CODE,
                 'dateTime' => date('Y-m-d H:i:s'),

             ),
         );
        $restponseTrack = $this->getRepository()->getByIdStatus(
                    $token,
                    MultiTrackingRepository::STATUS_ENABLED
                );
        if(empty($restponseTrack)) {
            $response['status']['code'] = BaseResponse::RESPONSE_STATUS_ERROR_CODE;
            $response['error']['code'] = self::ERROR_CODE_901;
            $response['error']['message'] = self::ERROR_MESSAGE_901;
            return $response;
        }
        
        $responseUpdateTrack = $this->getRepository()->updateMultiTrackingByToken(
                array(
                    'status' => MultiTrackingRepository::STATUS_DISABLED,
                    'editionDate' => date('Y-m-d H:i:s'),
                ),
                $token);
        if(!$responseUpdateTrack['success']) {
            $response['status']['code'] = BaseResponse::RESPONSE_STATUS_ERROR_CODE;
            $response['error']['code'] = self::ERROR_CODE_900;
            $response['error']['message'] = $e->getMessage();
        } else {
            $this->getServiceRequestService()
                ->getRepository()
                ->insert(
                        array(
                            'request' => json_encode($params),
                            'requestResponse' => json_encode($response),
                            'serviceId'=> $params['serviceId'],
                            'apikeyId'=> $params['apikeyId'],
                            'requestDate' => date('Y-m-d H:i:s')
                        )
                    );
            $this->getServiceApikeyService()
                ->save(array(
                    'serviceId' => $params['serviceId'],
                    'apikeyId' => $params['apikeyId'],
                ));
        }
        return $response;
    }
   
    /**
     * 
     * @param type $token
     * @param type $params
     * @return type
     */
    public function updateMultiTracking($token, $params)
    {
        $response = array(
             'status' => array(
                 'code' => BaseResponse::RESPONSE_STATUS_SUCCESS_CODE,
                 'dateTime' => date('Y-m-d H:i:s'),

             ),
         );
        $restponseTrack = $this->getRepository()->getByIdStatus(
                    $token,
                    MultiTrackingRepository::STATUS_ENABLED
                );
        if(empty($restponseTrack)) {
            $response['status']['code'] = BaseResponse::RESPONSE_STATUS_ERROR_CODE;
            $response['error']['code'] = self::ERROR_CODE_901;
            $response['error']['message'] = self::ERROR_MESSAGE_901;
            return $response;
        }
        
        $responseUpdateTrack = $this->getRepository()->updateMultiTrackingByToken(
                array(
                    'trackingKeys' => $data['trackings'],
                    'editionDate' => date('Y-m-d H:i:s'),
                ),
                $token);
        if(!$responseUpdateTrack['success']) {
            $response['status']['code'] = BaseResponse::RESPONSE_STATUS_ERROR_CODE;
            $response['error']['code'] = self::ERROR_CODE_900;
            $response['error']['message'] = $e->getMessage();
        } else {
            $this->getServiceRequestService()
                ->getRepository()
                ->insert(
                        array(
                            'request' => json_encode($params),
                            'requestResponse' => json_encode($response),
                            'serviceId'=> $params['serviceId'],
                            'apikeyId'=> $params['apikeyId'],
                            'requestDate' => date('Y-m-d H:i:s')
                        )
                    );
            $this->getServiceApikeyService()
                ->save(array(
                    'serviceId' => $params['serviceId'],
                    'apikeyId' => $params['apikeyId'],
                ));
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
    
    /**
     * @return ServiceApikeyService
     */
    private function getServiceApikeyService()
    {
        return $this->getServiceLocator()->get('Model\ServiceApikeyService');
    }
    
    /**
     * @return ServiceRequestService
     */
    private function getServiceRequestService()
    {
        return $this->getServiceLocator()->get('Model\ServiceRequestService');
    }
}