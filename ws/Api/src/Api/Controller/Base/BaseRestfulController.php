<?php

namespace Api\Controller\Base;

use Zend\Mvc\Controller\AbstractRestfulController;
use Api\Controller\Base\BaseResponse;

class BaseRestfulController extends AbstractRestfulController
{
    public $apikeyId;

    const ERROR_MESSAGE_501 = 'Apikey no valido';
       
    public $skeletonResponse = array(
        'status' => array(
            'code' => BaseResponse::RESPONSE_STATUS_SUCCESS_CODE,
            'dateTime' => '',
        )
    );
        
    public function init()
    {
        $params = $this->getRequestParams();
        $headerParams = apache_request_headers();
        $params['key'] = null;
        if (!empty($headerParams['key'])) {
            $params['key'] = $headerParams['key'];
        }

        $responseValidation = array();
        if(empty($params['key'])) {
            $responseValidation[] = array(
                'code' => 1,
                'field' => '',
                'message' => 'Key no se ha enviado',
            );
        } else {
            $keyresponse= $this->getApiKeyService()
                            ->getRepository()->getByKey($params['key']);
            if (empty($keyresponse)) {
                $responseValidation[] = array(
                    'code' => 1,
                    'field' => '',
                    'message' => 'Key invalido',
                );
            } else {
                $this->apikeyId = $keyresponse['apikeyId'];
            }
        }
        if (!empty($responseValidation)) {
            $this->skeletonResponse['status']['code'] = BaseResponse::RESPONSE_STATUS_ERROR_CODE;
            $this->skeletonResponse['error']['code'] = BaseResponse::STATUS_CODE_501;
            $this->skeletonResponse['error']['message'] = self::ERROR_MESSAGE_501;
            $this->skeletonResponse['error']['errors'] = $responseValidation;
            json_encode($this->skeletonResponse);            
        }
    }

    public function getList()
    {
               
    }

    public function get($id)
    {
        
    }

    public function create($data)
    {
       
    }

    public function update($id, $data)
    {

    }

    public function delete($id)
    {

    }
    
    public function getRequestParams()
    {
        $paramsRoute = $this->params()->fromRoute();
        $paramsPost = $this->params()->fromPost();
        $paramsQuery = $this->params()->fromQuery();
        
        return array_merge($paramsRoute, $paramsPost, $paramsQuery);
    }
    
     /**
     * @return \Apikey\Model\Service\ApikeyService
     */
    private function getApiKeyService()
    {
        return $this->getServiceLocator()->get('Model\ApikeyService');
    }
}
