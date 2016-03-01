<?php

namespace Api\Model\Gateway\Carrier\Ws;

use Zend\Soap\Client;

use Api\Model\Gateway\Carrier\Ws\Fedex\Fedex;

class FedexWs extends Fedex
{
    protected $soapClient;
    
    private $request = array();
    
    public function __construct($config)
    {        
        ini_set("soap.wsdl_cache_enabled", "0");
        //$soapClient = new Client($wsdl, $options);
        $wsdlFile = __DIR__ . '/Fedex/Wsdl/TrackService_v10.wsdl';       
                
        parent::__construct($wsdlFile, $config['key'],
                $config['password'], $config['accountNumber'], $config['meterNumber']);

        // TODO: Set this in env()?
        $this->endPoint = 'https://wsbeta.fedex.com:443/web-services';

        $this->setCustomerTransactionId('Track Request via PHP');
        
        $this->setVersion('trck', 10, 0, 0);
        
    }
        
    public function getTracking()
    {
        return 'OKS';
    }

    public function getMultitracking()
    {
        
    }
    
    /**
     *  Gets the tracking detials for the
     *  given tracking number and returns
     *  the FedEx request as an object.
     *
     *  @param string   // Tracking #
     *  @return SoapClient Object
     */
    public function getByTrackingNumber($trackingNumber) 
    {
        $trackingNumber = '123456789012';
        echo 'hi'; exit;
    	// Request syntax needed to track by tracking id\
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