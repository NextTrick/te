<?php
namespace Track\Model\Service;

use Util\Model\Service\Base\AbstractService;

class TrackService extends AbstractService
{
    public $requestError;
    
    public function create($params)
    {
        $params = $this->getParams();
//        var_dump($params); exit;
        $this->checkRequiredParams($params);
        
        if (!empty($this->requestError)) {
//            return $this->requestError;
            var_dump($this->requestError); exit;
        }
        var_dump($this->requestError); exit;
        
        $tablesData = $this->getTablesData($params);                
    }
    
    public function getTablesData($params)
    {
        $eventData = $params['events'];
        $shipmentData = $params['shipmentInfo'];        
        unset($params['events']);
        unset($params['shipmentInfo']);
        $trackData = $params;
        
        $returnData = array(
            'events' => 'eventData',
            'shipmentInfo' => 'shipmentData',
            'default' => 'trackData',            
        );
        
        $skeletonParams = $this->getSkeleton();        
                
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $inKey => $inValue) {
                            $table = $this->setValue($skeletonParams, $inKey, $inValue, $table);
                        }
                    } else {
                        $table = $this->setValue($skeletonParams, $k, $v, $table);
                    }                    
                }
            } else {
                $table = $this->setValue($skeletonParams, $key, $value, $table);
            }
        }
        
        return $returnData;
    }
    
    public function setValue($skeletonParams, $key, $value, $table)
    {      
        if (!empty($value)) {
            $table[$skeletonParams[$key]['dbColumn']] =  $value;
        }
        
        return $table;
    }
        
    public function checkRequiredParamsOK($params)
    {
        $skeletonParams = $this->getSkeleton();
        
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $inKey => $inValue) {
                             $this->checkRequiredValue($skeletonParams, array($key, $k, $inKey),  $inValue);
                        }
                    } else {
                         $this->checkRequiredValue($skeletonParams, array($key, $k),  $v);
                    }                   
                }
            } else {
                $this->checkRequiredValue($skeletonParams, array($key), $value);
            }
        }
    }
    
    public function checkRequiredParams($params)
    {                
        $skeletonParams = $this->getSkeleton();
        
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
                                    $this->checkRequiredValue($skeletonParams, $params, array($key, $k, $inKey, $inK));
                                }
                            } else {
                                //var_dump($key, $k, $inKey, 2); exit;
                                $this->checkRequiredValue($skeletonParams, $params, array($key, $k, $inKey)); 
                            }                                                                                                                                            
                        }                                
                    } else {                            
                        $this->checkRequiredValue($skeletonParams, $params, array($key, $k));    
                    }                                                                                              
                }
            } else {
                $this->checkRequiredValue($skeletonParams, $params, array($key)); 
            }                                            
        }
    }
    
    public function checkRequiredValueOK($skeletonParams,$keys, $value)
    {
        $validation = $this->getValidation($skeletonParams, $keys);
        
        $keyString = implode('|', $keys);
        switch ($validation) {
            case 'REQUIRED':
                if (empty($value)) {                    
                    $this->requestError['errors'][$keyString]['code'] = 901;
                    $this->requestError['errors'][$keyString]['field'] = $keyString;
                    $this->requestError['errors'][$keyString]['message'] = "Param required";
                } 
                break;
            case 'NOT REQUIRED':
                break;
            case 'UNKNOWN':
                $this->requestError['errors'][$keyString]['code'] = 902;
                $this->requestError['errors'][$keyString]['field'] = $keyString;
                $this->requestError['errors'][$keyString]['message'] = "Unknown Param";
                break;
            default:
                break;
        }       
    }
    
    public function checkRequiredValue($skeletonParams, $params, $keys)
    {
        $validation = $this->getValidation($skeletonParams, $params, $keys);
        
        $keyString = implode('|', $keys);
        switch ($validation) {
            case 'FAIL REQUIRED':                                 
                $this->requestError['errors'][$keyString]['code'] = 901;
                $this->requestError['errors'][$keyString]['field'] = $keyString;
                $this->requestError['errors'][$keyString]['message'] = "Param required";
                break;
            case 'UNKNOWN':
                $this->requestError['errors'][$keyString]['code'] = 902;
                $this->requestError['errors'][$keyString]['field'] = $keyString;
                $this->requestError['errors'][$keyString]['message'] = "Unknown Param";
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
                //var_dump($return, $key, $keys); exit;
                break;
            }         
        }
        
       //var_dump($return, $key); exit;
        
        return $return;
    }
    
    public function getValidationOK($skeletonParams, $keys)
    {
        $tempSkeleton = $skeletonParams;
        $return = 'UNKNOWN';
        foreach ($keys as $k => $key) {
            if (isset($tempSkeleton[$key])) {
                if ((count($keys) - 1) == $k) {
                    if ($tempSkeleton[$key]['required'] == true) {
                        $return = 'REQUIRED';
                    } else {
                        $return = 'NOT REQUIRED';
                    }
                } else {
                    $tempSkeleton = $tempSkeleton[$key];
                }                                              
            } else {
                break;
            }         
        }
        
        return $return;
    }
    
    public static function getSkeleton()
    {
        return array(
            'trackingKey' => array(
                'required' => false,
                'dbColumn' => 'trackingKey',
            ),
            'carrierCode' => array(
                'required' => true,
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
    
    public function getParams()
    {        
        return array (
            'trackingKey' => '32421231f',
            'carrierCode' => 'FCB',
            'operatingCompanyOrCarrierDescription' => 'Fedex',            
            'originAddress' => array(
                'stateOrProvinceCode' => 'EA',
                'countryCode' => 'EU',                
            ),            
            'destinationAddress' => array(
                'stateOrProvinceCode' => 'AE',
                'countryCode' => 'EU',                                
            ),
            'events' => array(
                '1' => array(
                    'dateTime' => '2016-15-29 12:09:09',                    
                    'eventCode' => 'DELIVERY',
                    'eventDescription' => 'Box was delivered',
                    'address' => array(                        
                        'stateOrProvinceCode' => 'AB',                        
                        'countryCode' => 'AU',                                                
                    ),                    
                )
            ),
            'shipmentInfo' => array(
                'weight' => array(
                    'value' => '21',
                    'units' => 'kg',
                ),
                'dimensions' => array(
                    'length' => '40',
                    'width' => '23',
                    'height' => '15',
                    'units' => 'mt',
                ),                                             
                'numberOfPieces' => '5',
                'packageSequenceNumber' => '123131',                
                'packaging' => '3234',
                'service' =>  array(                    
                    'description' => 'Fedex Premiun'                    
                ),                
                'pickupDateTime' => '2016-11-29 12:09:09', //shipTimestamp on fedex                                
                'lastUpdated' => '2016-16-29 12:09:09', //ActualDeliveryTimestamp on fedex
            )
        );      
    }    
}