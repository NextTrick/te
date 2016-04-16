<?php
namespace Track\Model\Request;

use Api\Controller\Base\BaseResponse;
use Track\Model\Request\Base\Request;

class TrackRequest extends Request
{         
    public function checkRequiredParams()
    {
        $skeleton = 'get' . ucfirst($this->method) . 'Skeleton';
        $skeletonParams = $this->$skeleton();
        
        $requiredCols = array('required', 'dbColumn');
        
        foreach ($skeletonParams as $key => $value) {             
            if (!isset($value['dbColumn'])) { 
                foreach ($value as $k => $v) {
                    if (in_array($k, $requiredCols)) {
                        continue;
                    }                                              
                    if (!isset($v['dbColumn'])) {
                        foreach ($v as $inKey => $inValue) {
                            if (in_array($inKey, $requiredCols)) {
                                continue;
                            }                                                                     
                            if (!isset($inValue['dbColumn'])) {                                    
                                foreach ($inValue as $inK => $inV) {                                        
                                    $this->checkRequiredValue($skeletonParams, $this->params, array($key, $k, $inKey, $inK));
                                }
                            } else {                                
                                $this->checkRequiredValue($skeletonParams, $this->params, array($key, $k, $inKey)); 
                            }                                                                                                                                            
                        }                                
                    } else {                            
                        $this->checkRequiredValue($skeletonParams, $this->params, array($key, $k));    
                    }                                                                                              
                }
            } else {
                $this->checkRequiredValue($skeletonParams, $this->params, array($key)); 
            }                                            
        }
    }
    
    public function checkRequiredValue($skeletonParams, $params, $keys)
    {
        $validation = $this->getValidation($skeletonParams, $params, $keys);
        
        $keyString = implode('|', $keys);
        switch ($validation) {
            case 'FAIL REQUIRED':                                 
                $this->errors['errors'][$keyString]['code'] = BaseResponse::ERROR_CODE_901;
                $this->errors['errors'][$keyString]['field'] = $keyString;
                $this->errors['errors'][$keyString]['message'] = BaseResponse::ERROR_MESSAGE_901;
                break;
            case 'UNKNOWN':
                $this->errors['errors'][$keyString]['code'] = BaseResponse::ERROR_CODE_902;
                $this->errors['errors'][$keyString]['field'] = $keyString;
                $this->errors['errors'][$keyString]['message'] = BaseResponse::ERROR_MESSAGE_902;
                break;
            default:
                break;
        }       
    }
    
    public function getValidation($skeletonParams, $params, $keys)
    {
        $tempSkeleton = $skeletonParams;
        $tempParams = $params;
                
        $return = 'OK REQUIRED';
        foreach ($keys as $k => $key) {            
            if (isset($tempParams[$key])) {
                if ((count($keys) - 1) == $k) {
                    if ($tempSkeleton[$key]['required'] == true) {                        
                        if (empty($tempParams[$key])) {                            
                            $return = 'FAIL REQUIRED';
                        }                                                
                    } else {                        
                        $return = 'OK REQUIRED';                        
                    }
                } else {
                    $tempParams = $tempParams[$key];
                    $tempSkeleton = $tempSkeleton[$key];
                }                                              
            } else {
                if ($tempSkeleton[$key]['required'] == true) {                        
                    $return = 'FAIL REQUIRED'; 
                }                
                break;
            }         
        }
        
        return $return;
    }
    
    public static function getCreateSkeleton()
    {
        return array(
            'trackingKey' => array(
                'required' => false,
                'dbColumn' => 'trackingKey',
            ),
            'carrierCode' => array(
                'required' => false,
                'dbColumn' => 'carrierCode',
            ),
            'operatingCompanyOrCarrierDescription' => array(
                'required' => true,
                'dbColumn' => 'operatingCompanyOrCarrierDescription',
            ),            
            'originAddress' => array(
                'stateOrProvinceCode' => array(
                    'required' => true,
                    'dbColumn' => 'stateOrProvinceCode',
                ),
                'countryCode' => array(
                    'required' => true,
                    'dbColumn' => 'countryCode',
                ),
                'required' => true,            
            ),            
            'destinationAddress' => array(
                'stateOrProvinceCode' => array(
                    'required' => true,
                    'dbColumn' => 'stateOrProvinceCode',
                ),
                'countryCode' => array(
                    'required' => true,
                    'dbColumn' => 'countryCode',
                ),
                'required' => true,
            ),
            'events' => array(
                '1' => array(
                    'dateTime' => array(
                        'required' => true,
                        'dbColumn' => 'date',
                    ),                    
                    'eventCode' => array(
                        'required' => true,
                        'dbColumn' => 'code',
                    ),
                    'eventDescription' => array(
                        'required' => false,
                        'dbColumn' => 'eventDescription',
                    ),
                    'address' => array(                    
                        'stateOrProvinceCode' => array(
                            'required' => true,
                            'dbColumn' => 'stateOrProvinceCode',
                        ),
                        'countryCode' => array(
                            'required' => true,
                            'dbColumn' => 'countryCode',
                        ),
                        'required' => true,                
                    ),   
                    'required' => true
                ),
                'required' => true          
            ),
            'shipmentInfo' => array(
                'weight' => array(
                    'value' => array(
                        'required' => true,
                        'dbColumn' => 'weightValue',
                    ),
                    'units' => array(
                        'required' => true,
                        'dbColumn' => 'weightUnits',
                    ),
                    'required' => true,                    
                ),
                'dimensions' => array(
                    'length' => array(
                        'required' => true,
                        'dbColumn' => 'dimensionLength',
                    ),
                    'width' => array(
                        'required' => true,
                        'dbColumn' => 'dimensionWidth',
                    ),
                    'height' => array(
                        'required' => true,
                        'dbColumn' => 'dimensionHeight',
                    ),
                    'units' => array(
                        'required' => true,
                        'dbColumn' => 'dimensionUnits',
                    ),
                    'required' => true,                    
                ),                
                'notification' => array(
                    'code' => array(
                        'required' => false,
                        'dbColumn' => 'code',
                    ),
                    'message' => array(
                        'required' => false,
                        'dbColumn' => 'message',
                    ),
                    'required' => false,                   
                ),                
                'numberOfPieces' => array(
                    'required' => false,
                    'dbColumn' => 'numberOfPieces',
                ),
                'packageSequenceNumber' => array(
                    'required' => false,
                    'dbColumn' => 'packageSequenceNumber',
                ),                
                'packaging' => array(
                    'required' => false,
                    'dbColumn' => 'packaging',
                ),
                'service' =>  array(                    
                    'description' => array(
                        'required' => false,
                        'dbColumn' => 'serviceDescription',
                    ),
                    'required' => false,                    
                ),                
                'pickupDateTime' => array(
                    'required' => true,
                    'dbColumn' => 'pickupDateTime',
                ), //shipTimestamp on fedex                                
                'lastUpdated' => array(
                    'required' => true,
                    'dbColumn' => 'lastUpdated',
                ), //ActualDeliveryTimestamp on fedex
                'required' => true,                
            )        
        );
    }
    
    public function getUpdateSkeleton() 
    {
        return array(
            'trackingKey' => array(
                'required' => true,
                'dbColumn' => 'trackingKey',
            ),
            'carrierCode' => array(
                'required' => false,
                'dbColumn' => 'carrierCode',
            ),
            'operatingCompanyOrCarrierDescription' => array(
                'required' => false,
                'dbColumn' => 'operatingCompanyOrCarrierDescription',
            ),            
            'originAddress' => array(
                'stateOrProvinceCode' => array(
                    'required' => false,
                    'dbColumn' => 'stateOrProvinceCode',
                ),
                'countryCode' => array(
                    'required' => false,
                    'dbColumn' => 'countryCode',
                ),
                'required' => true,            
            ),            
            'destinationAddress' => array(
                'stateOrProvinceCode' => array(
                    'required' => false,
                    'dbColumn' => 'stateOrProvinceCode',
                ),
                'countryCode' => array(
                    'required' => false,
                    'dbColumn' => 'countryCode',
                ),
                'required' => false,
            ),            
            'shipmentInfo' => array(
                'weight' => array(
                    'value' => array(
                        'required' => false,
                        'dbColumn' => 'weightValue',
                    ),
                    'units' => array(
                        'required' => false,
                        'dbColumn' => 'weightUnits',
                    ),
                    'required' => false,                    
                ),
                'dimensions' => array(
                    'length' => array(
                        'required' => false,
                        'dbColumn' => 'dimensionLength',
                    ),
                    'width' => array(
                        'required' => false,
                        'dbColumn' => 'dimensionWidth',
                    ),
                    'height' => array(
                        'required' => false,
                        'dbColumn' => 'dimensionHeight',
                    ),
                    'units' => array(
                        'required' => false,
                        'dbColumn' => 'dimensionUnits',
                    ),
                    'required' => false,                    
                ),                
                'notification' => array(
                    'code' => array(
                        'required' => false,
                        'dbColumn' => 'code',
                    ),
                    'message' => array(
                        'required' => false,
                        'dbColumn' => 'message',
                    ),
                    'required' => false,                   
                ),                
                'numberOfPieces' => array(
                    'required' => false,
                    'dbColumn' => 'numberOfPieces',
                ),
                'packageSequenceNumber' => array(
                    'required' => false,
                    'dbColumn' => 'packageSequenceNumber',
                ),                
                'packaging' => array(
                    'required' => false,
                    'dbColumn' => 'packaging',
                ),
                'service' =>  array(                    
                    'description' => array(
                        'required' => false,
                        'dbColumn' => 'serviceDescription',
                    ),
                    'required' => false,                    
                ),                
                'pickupDateTime' => array(
                    'required' => false,
                    'dbColumn' => 'pickupDateTime',
                ), //shipTimestamp on fedex                                
                'lastUpdated' => array(
                    'required' => false,
                    'dbColumn' => 'lastUpdated',
                ), //ActualDeliveryTimestamp on fedex
                'required' => false,                
            )        
        );
    }
    
    public function getDeleteSkeleton() 
    {
        return array(
            'trackingKey' => array(
                'required' => true,
                'dbColumn' => 'trackingKey',
            ),            
        );
    }
}