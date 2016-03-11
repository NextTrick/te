<?php

namespace Api\Controller\Base;

use Zend\Mvc\Controller\AbstractRestfulController;
use Api\Controller\Base\BaseResponse;
use Util\Common\Filter;

class BaseRestfulController extends AbstractRestfulController
{
    public $apikeyId;
    
    public $apiKeyData = array();

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
        
        $paramsValidation = array();
        if(empty($params['key'])) {
            $paramsValidation[] = array(
                'code' => 1,
                'field' => '',
                'message' => 'Key no se ha enviado',
            );
        } else {
            $accountDbAdapter = $this->getServiceLocator()->get('accountManagerDb');
            $adapter = $this->getApiKeyService()
                            ->getRepository()->getAdapter(); 
            
            $this->getApiKeyService()->getRepository()->setAdapter($accountDbAdapter);            
            $apikeyData = $this->getApiKeyService()->getRepository()
                    ->getByKeyServiceId($params['key'], $this::ENDPOINT_TRACKING_ID);        
            $this->getApiKeyService()->getRepository()->setAdapter($adapter); 
            
            if (empty($apikeyData)) {
                $paramsValidation[] = array(
                    'code' => 1,
                    'field' => '',
                    'message' => 'Key invalido',
                );
            } else {
                $this->apikeyId = $apikeyData['apikeyId'];
                $this->apikeyData = $apikeyData;
            }
        }
        if (!empty($paramsValidation)) {
            $this->skeletonResponse['status']['code'] = BaseResponse::RESPONSE_STATUS_ERROR_CODE;
            $this->skeletonResponse['error']['code'] = BaseResponse::STATUS_CODE_501;
            $this->skeletonResponse['error']['message'] = self::ERROR_MESSAGE_501;
            $this->skeletonResponse['error']['errors'] = $paramsValidation;
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
        $paramsRoute = Filter::trimStripTag($this->params()->fromRoute());
        $paramsPost = Filter::trimStripTag($this->params()->fromPost());
        $paramsQuery = Filter::trimStripTag($this->params()->fromQuery());
        
        $headerParams = apache_request_headers();
        $extraParams = array();
        if (!empty($headerParams['key'])) {
            $extraParams['key'] = Filter::trimStripTag($headerParams['key']);
        }
        
//        $extraParams['key'] = '2342FF2343223FFFSS'; 
        
        return array_merge($paramsRoute, $paramsPost, $paramsQuery, $extraParams);
    }
    
     /**
     * @return \Apikey\Model\Service\ApikeyService
     */
    private function getApiKeyService()
    {
        return $this->getServiceLocator()->get('Model\ApikeyService');
    }
}
