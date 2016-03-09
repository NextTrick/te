<?php

namespace Api\Model\Gateway\Carrier\Ws;

use Api\Model\Gateway\Carrier\Ws\Base\BaseWs;

class UpsWs extends BaseWs
{   
    private $request = array();
        
    protected $password;   
    protected $userName;    
    protected $serviceAccessToken;        
            
    const STATUS_ERROR = 'Error';
    const STATUS_FAILURE = 'FAILURE';
    const STATUS_WARNING = 'WARNING';
    const STATUS_NOTE = 'NOTE';
    const STATUS_SUCCESS = 'Success';
    
    protected $errorStatus = array(
        self::STATUS_ERROR,
    );

    public function __construct($config)
    {        
        ini_set("soap.wsdl_cache_enabled", "0");
                       
        $this->password = $config['password'];
        $this->userName = $config['userName'];
        $this->serviceAccessToken = $config['serviceAccessToken'];    
        $endpoint = $config['endpoint'];
        
        $wsdlFile = __DIR__ . '/Ups/Wsdl/Track.wsdl';       
       
        $this->client = new \SoapClient($wsdlFile, array('trace' => 1));       
        $this->client->__setLocation($endpoint);
    }
        
    public function getByTrackingNumber($trackingNumber)
    {                  
        $responseData = array('success' => true);         
        $request = $this->prepareRequest($trackingNumber);        
        try {
            $response = $this->client->ProcessTrack($request);
//            var_dump($this->client->__getLastResponse()); exit;
            $stausDescription = !empty($Response->ResponseStatus->Description) 
                ? $Response->ResponseStatus->Description : null;
                
            if (!empty($stausDescription) && $stausDescription == self::STATUS_SUCCESS) {
                $responseData['data'] = $response;
            } else {
                if (!empty($response->Errors->ErrorDetail->PrimaryErrorCode)) {
                    $responseData = $this->parseError($response);
                } else {
                    $responseData = $this->getGenericErrorData();
                }
            }         
        } catch (\Exception $e) {            
            $responseData['success'] = false;                        
            $responseData = $this->getGenericErrorData();                
            $responseData['error']['message'] = $e->getMessage();
            $responseData['error']['exception'] = $e->getTraceAsString();                      
        }
        
        return $responseData;
    }
    
    public function getGenericErrorData()
    {
        return array('success' => false,
                     'error' => array('code' => self::ERROR_GENERIC_CODE,
                                      'message' => self::ERROR_GENERIC_MESSAGE));
    }
    
    protected function parseError($response)
    {
        $return = $this->getGenericErrorData();
        
        if (!empty($response->Errors->ErrorDetail->PrimaryErrorCode)) { 
            $tempError = array('');
//            foreach ($response->ErrorDetail as $notification) {                
                $tempError['code'][] = $response->Errors->ErrorDetail->PrimaryErrorCode->Code;
                $tempError['message'][] = $response->Errors->ErrorDetail->PrimaryErrorCode->Description;
                $tempError['aditionalMessage'][] = $response->Errors->ErrorDetail->PrimaryErrorCode->Digest;              
//            }
            
            $returnError = array();
            $returnError['code'] = implode('|', $tempError['code']);
            $returnError['message'] = implode('|', $tempError['message']);
            $returnError['aditionalMessage'] = implode('|', $tempError['aditionalMessage']);
            
            $return['error'] = $returnError;
        } 
        
        return $return;
    }
    
    public function prepareRequest($trackingNumber)
    {
        $this->request = array(
            'UPSSecurity' => array(
                'UsernameToken' => array(                
                    'Username' => $this->userName,
                    'Password' => $this->password,                
                ),
                'ServiceAccessToken' => array(
                    'AccessLicenseNumber' => $this->accessLicenseNumber,                
                ),
            ),
            
            'Request' => array(
                'TransactionReference' => array(
                    'CustomerContext' => 'Add description here'
                ),
                'RequestOption' => '1',
            ),            
            'InquiryNumber' => $trackingNumber,
            'TrackingOption' => '02'
        );
        
        return $this->request;
    }
      
    protected function parseXml($xmlString)
    {
        $xml = simplexml_load_string($xmlString);
        return json_decode(json_encode($xml), TRUE);
    }
}