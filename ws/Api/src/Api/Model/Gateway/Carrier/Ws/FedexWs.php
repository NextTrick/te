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
        $trackingNumber = '123456789012';  
        $request = $this->prepareRequest($trackingNumber);
        
        try {
            $response = $this->soapClient->track($request);
            echo get_class($response); exit;
            var_dump($this->soapClient->getLastRequest(), $this->soapClient->getLastResponse()); exit;
        } catch (\Exception $e) {            
            var_dump($e->getMessage(), $e->getTraceAsString(),
                    $this->soapClient->getLastRequest(), $this->soapClient->getLastResponse()); exit;
        }
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
    
    /**
     *  Gets the tracking detials for the
     *  given tracking number and returns
     *  the FedEx request as an object.
     *
     *  @param string   // Tracking #
     *  @return SoapClient Object
     */
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