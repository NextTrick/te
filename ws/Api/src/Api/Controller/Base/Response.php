<?php

namespace Api\Controller\Base;
use Api\Controller\Base\BaseResponse;

class Response 
{
    public $response;
    
    public function __construct() 
    {
        $this->response = BaseResponse::getResponseSkeleton();
    }
    
    public function setResponseStatusSuccess()
    {
        $this->response['status']['code'] = BaseResponse::RESPONSE_STATUS_SUCCESS_CODE;
    }
    
    public function setResponseStatusError()
    {
        $this->response['status']['code'] = BaseResponse::RESPONSE_STATUS_ERROR_CODE;
    }
    
    public function setResponseData(array $data)
    {
        $this->response = $this->response + $data;
    }
    
    public function setErrorCode($code)
    {
        $this->response['error']['code'] = $code;                
    }
    
    public function setErrorMessage($message)
    {
        $this->response['error']['message'] = $message;                
    }
    
    public function setErrorDescription($description)
    {
        $this->response['error']['description'] = $description;                
    }
    
    public function setErrorErrors($errors)
    {
        $this->response['error']['errros'] = $errors;                
    }
    
    public function getArray()
    {
        return $this->getResponse;
    }
}
