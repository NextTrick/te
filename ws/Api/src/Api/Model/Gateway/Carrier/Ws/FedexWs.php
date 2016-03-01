<?php

namespace Api\Model\Gateway\Carrier\Ws;

use Zend\Soap\Client;

use Api\Model\Gateway\Carrier\Ws\Fedex\Fedex;

class FedexWs extends Fedex
{
    protected $soapClient;
    
    private $request = array();
    
    protected $key;    
    protected $password;   
    protected $accountNumber;    
    protected $meterNumber;    
    protected $customerTransactionId;
        
    const ERROR_GENERIC_CODE = 500;
    const ERROR_GENERIC_MESSAGE = 'Failed Ws connection';
    
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
       
        $this->key = $config['key'];
        $this->password = $config['password'];
        $this->accountNumber = $config['accountNumber'];
        $this->meterNumber = $config['meterNumber'];
        $this->meterNumber = $config['meterNumber'];
        $this->customerTransactionId = "Defualt ID";
        
        $wsdlFile = __DIR__ . '/Fedex/Wsdl/TrackService_v10.wsdl';       
        
        $this->soapClient = new Client($wsdlFile);        
    }
        
    public function getTracking()
    {
        return 'OKS';
    }

    public function getMultitracking()
    {
        
    }
    
    public function getByTrackingNumber($trackingNumber)
    {
        $responseData = array('success' => true);        
        $trackingNumber = '123456789012';  
        $request = $this->prepareRequest($trackingNumber);
        
        try {
            $response = $this->soapClient->track($request);                                  
//            var_dump($e->getMessage(), $e->getTraceAsString(),
//                    $this->soapClient->getLastRequest(), $this->soapClient->getLastResponse()); exit;
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
            $lastResponse = $this->soapClient->getLastResponse();
            if (!empty($lastResponse) && !empty($lastResponse->detail)) {
                $responseData['error']['code'] = $lastResponse->detail->code;
                $responseData['error']['message'] = $lastResponse->detail->desc;
                $responseData['error']['aditionalMessage'] = $lastResponse->detail->cause;
            } else {
                $responseData['error']['exception'] = $this->errorCodeGeneric;
                $responseData['error']['message'] = $e->getMessage();            
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
            'WebAuthenticationDetail' => array(
                'UserCredential' => array(
                    'Key' => $this->key,
                    'Password' => $this->password,
                ),                
            ),
            'ClientDetail' => array(
                'AccountNumber' => $this->accountNumber,
                'MeterNumber' => $this->meterNumber,
            ),
            'TransactionDetail' => array(
                'CustomerTransactionId' => $this->customerTransactionId,            
            ),
            'Version' => array(
                'ServiceId' => 'trck', 
                'Major' => 10, 
                'Intermediate' => 0, 
                'Minor' => 0          
            ),
            'SelectionDetails' => array(
                'CarrierCode' => 'FDXG', 
                'PackageIdentifier' => array(
                    'Type' => 'TRACKING_NUMBER_OR_DOORTAG',
                    'Value' => $trackingNumber
                ),       
            ),
            'ProcessingOptions' => 'INCLUDE_DETAILED_SCANS'
        );
        
        return $this->request;
    }
      
    public function getByTrackingNumberOk($trackingNumber) 
    {
        $trackingNumber = '123456789012';
        
    	// Request syntax needed to track by tracking id
    	$this->request['SelectionDetails'] = array(
            'PackageIdentifier' => array(
                    'Type' => 'TRACKING_NUMBER_OR_DOORTAG',
                    'Value' => $trackingNumber // Tracking ID to track
            )
        );

    	$req = $this->buildRequest($this->request);
        
        $response = $this->getSoapClient()->track($req);
        
        var_dump($response); exit;
    	return $this->getSoapClient()->track($req);
    }
}