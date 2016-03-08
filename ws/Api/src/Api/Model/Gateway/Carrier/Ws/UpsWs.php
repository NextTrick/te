<?php

namespace Api\Model\Gateway\Carrier\Ws;

use Api\Model\Gateway\Carrier\Ws\Base\BaseWs;

class UpsWs extends BaseWs
{   
    private $request = array();
        
    protected $password;   
    protected $userName;    
    protected $serviceAccessToken;        
            
    const STATUS_ERROR = 'ERROR';
    const STATUS_FAILURE = 'FAILURE';
    const STATUS_WARNING = 'WARNING';
    const STATUS_NOTE = 'NOTE';
    const STATUS_SUCCESS = 'SUCCESS';
    
    protected $errorStatus = array(
        self::STATUS_ERROR,
        self::STATUS_FAILURE,
    );

    public function __construct($config)
    {        
        ini_set("soap.wsdl_cache_enabled", "0");
                       
        $this->password = $config['password'];
        $this->userName = $config['userName'];
        $this->serviceAccessToken = $config['serviceAccessToken'];    
        
        $wsdlFile = __DIR__ . '/Ups/Wsdl/Track.wsdl';       
       
        $this->client = new \SoapClient($wsdlFile, array('trace' => 1));       
    }
        
    public function getByTrackingNumber($trackingNumber)
    {                  
        $responseData = array('success' => true);         
        $request = $this->prepareRequest($trackingNumber);        
        try {
            $response = $this->client->ProcessTrack($request);
//            var_dump($this->client->__getLastResponse()); exit;
            if (!in_array($response->HighestSeverity, $this->errorStatus)) {
                $responseData['data'] = $response;
            } else {
                if (!empty($response->Notifications)) {
                    $responseData = $this->parseError($response);
                } else {
                    $responseData = $this->getGenericErrorData();
                }
            }         
        } catch (\Exception $e) {            
            $responseData['success'] = false; 
            $lastResponse = $this->client->__getLastResponse();
            if (!empty($lastResponse)) {
                $lastResponse = simplexml_load_string($lastResponse);                  
                if (!empty($lastResponse->detail)) {
                    $responseData['error']['code'] = $lastResponse->detail->code;
                    $responseData['error']['message'] = $lastResponse->detail->desc;
                    $responseData['error']['aditionalMessage'] = $lastResponse->detail->cause;
                } else {   
                    var_dump($this->client->getLastRequest(), $this->client->getLastResponse()); exit;
                    $responseData = $this->getGenericErrorData();                
                    $responseData['error']['message'] = $e->getMessage();
                    $responseData['error']['exception'] = $e->getTraceAsString();
                }
            } else {
                $responseData = $this->getGenericErrorData();                
                $responseData['error']['message'] = $e->getMessage();
                $responseData['error']['exception'] = $e->getTraceAsString();
            }            
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
        
        if (!empty($response->Notifications)) { 
            $tempError = array('');
            foreach ($response->Notifications as $notification) {                
                $tempError['code'][] = $notification->Code;
                $tempError['message'][] = $notification->Message;
                $tempError['aditionalMessage'][] = $notification->LocalizedMessage;              
            }
            
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