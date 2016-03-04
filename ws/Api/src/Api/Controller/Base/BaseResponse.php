<?php

namespace Api\Controller\Base;

use Zend\Http\Response;

class BaseResponse extends Response
{
    const ERROR_MESSAGE_500 = 'Internal server error';
    
    const RESPONSE_STATUS_SUCCESS_CODE = 'SUCCESS';
    
    const RESPONSE_STATUS_ERROR_CODE = 'ERROR';
        
    public static function getErrorSkeleton()
    {
        return array(
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
