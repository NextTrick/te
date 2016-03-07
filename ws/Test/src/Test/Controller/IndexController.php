<?php

namespace Test\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public $httpClient;
    
    public function trackingAction()
    {                               
        $url = 'http://trackingengine.bongous.dev/tracking/928282828';         
        
        $this->httpClient = new \Zend\Http\Client(null, array(
            'adapter' => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 120,
            ),
        ));
                   
//        $this->httpClient->setAuth($this->username, $this->password);        
        $this->httpClient->setUri($url);
        $this->httpClient->setMethod('GET');      
        $this->httpClient->setParameterGet(array('key' => 1111243424242));   
        $this->httpClient->setHeaders(array(            
            'key' => "Basic234223234424242",
        ));
//        $this->httpClient->setAuth('user_sdfasfs', 'pas_sdfasfs');
//        $this->setHeaders($this->httpClient);
        
        try {
            $response = $this->httpClient->send();                        
            var_dump($response); exit;
            if ($response->isSuccess()) {
                $responseXml = $this->parseXml($response->getBody());                
                $responseData['data'] = $responseXml;
            } else {
                $responseXml = $this->parseXml($response->getBody());
                $responseData = $this->parseError($responseXml);
                $responseData['success'] = false;
            }           
        } catch (\Exception $e) {
            var_dump($e->getMessage(), $e->getTraceAsString()); exit;
            $responseData['success'] = false;   
            $responseData['exception'] = $this->errorCodeGeneric;
            $responseData['message'] = $e->getMessage();
        }
        
        return $responseData;
    }
}
