<?php

namespace Api\Controller\Base;

use Zend\Mvc\Controller\AbstractRestfulController;
use Api\Controller\Base\BaseResponse;
use Util\Common\Filter;
use Service\Model\Repository\ServiceRepository;
use Statistic\Model\Service\ServiceApikeyService;
use Api\Controller\Base\Response;

class BaseRestfulController extends AbstractRestfulController
{
    public $apikeyId;

    public $serviceApikeyId;
    
    const ENDPOINT_TRACKING_ID = ServiceRepository::ENDPOINT_TRACKING_ID;
    
    const METHOD_CREATE= 'create';
    const METHOD_UPDATE = 'update';
    const METHOD_DELETE = 'delete';
    const METHOD_GET = 'get';

    public function init()
    {
        $this->setIdentifierName('param1');        
        $params = $this->getRequestParams();
        
        $response = new Response();
        $response->setResponseStatusError();
        if (empty($params['key'])) {            
            $response->setErrorCode(BaseResponse::ERROR_CODE_900);
            $response->setErrorCode(BaseResponse::ERROR_MESSAGE_900);            
            $errors = array(
                'code' => BaseResponse::ERROR_CODE_901,
                'field' => 'key',
                'message' => BaseResponse::ERROR_MESSAGE_901,
            );
            $response->setErrorErrors($errors);
            
        } else {
            $accountDbAdapter = $this->getServiceLocator()->get('accountManagerDb');
            $adapter = $this->getApiKeyService()
                            ->getRepository()->getAdapter(); 
            
            $this->getApiKeyService()->getRepository()->setAdapter($accountDbAdapter);            
            $apikeyData = $this->getApiKeyService()->getRepository()
                    ->getByKeyServiceId($params['key'], $this::ENDPOINT_TRACKING_ID);        
            $this->getApiKeyService()->getRepository()->setAdapter($adapter); 
            
            if (empty($apikeyData)) {
                $response->setErrorCode(BaseResponse::ERROR_CODE_900);
                $response->setErrorCode(BaseResponse::ERROR_MESSAGE_900);            
                $errors = array(
                    'code' => BaseResponse::ERROR_CODE_903,
                    'field' => 'key',
                    'message' => BaseResponse::ERROR_MESSAGE_903,
                );
                
                $response->setErrorErrors($errors);                           
            } else {
                $this->saveServiceApikeyData($params['key'], $apikeyData['profileId']);                               
            }
        }
                        
        if ($response->hasError()) {
            echo json_encode($response->getArray()); exit;
        }
    }
    
    protected function saveServiceApikeyData($key, $profileId)
    {
        $apikeyId = $this->getApikeyService()
                ->getApikeyIdByKeyProfileId($params['key'], $apikeyData['profileId']);

        $serviceApikeyData = array(
            'serviceId' => $this::ENDPOINT_TRACKING_ID,
            'apikeyId' => $apikeyId,
        );

        $serviceApikeyId = $this->getServiceApikeyService()
                ->save($serviceApikeyData);

        $this->apikeyId = $apikeyId;
        $this->serviceApikeyId = $serviceApikeyId; 
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
    
    /**
     * @return ServiceApikeyService
     */
    public function getServiceApikeyService()
    {
        return $this->serviceLocator->get('Model\ServiceApikeyService');
    }
}
