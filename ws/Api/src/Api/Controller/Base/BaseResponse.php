<?php

namespace Api\Controller\Base;

use Zend\Http\Response;

class BaseResponse extends Response
{
    const ERROR_MESSAGE_500 = 'Internal server error';
    
    const ERROR_CODE_900 = 900;    
    const ERROR_MESSAGE_900 = 'Params validation falied';
    
    const ERROR_CODE_901 = 901;
    const ERROR_MESSAGE_901 = 'Param required';
    
    const ERROR_CODE_902 = 902;
    const ERROR_MESSAGE_902 = 'Unknown Param';
    
    const RESPONSE_STATUS_SUCCESS_CODE = 'SUCCESS';    
    const RESPONSE_STATUS_ERROR_CODE = 'ERROR';
    
    public static function getResponseSkeleton()
    {
        return  array(
            'status' => array(
                'code' => self::RESPONSE_STATUS_ERROR_CODE,
                'dateTime' => date('Y-m-d H:i:s'),
            )            
        );        
    }
        
    public static function getErrorSkeleton()
    {
        return  array(
            'status' => array(
                'code' => self::RESPONSE_STATUS_ERROR_CODE,
                'dateTime' => date('Y-m-d H:i:s'),
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
    }
}
