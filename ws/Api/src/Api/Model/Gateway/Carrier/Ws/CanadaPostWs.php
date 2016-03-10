<?php

namespace Api\Model\Gateway\Carrier\Ws;

use Api\Model\Gateway\Carrier\Ws\Base\BaseWs;
use Zend\Http\Client;

class CanadaPostWs extends BaseWs
{
    public $config;
            
    public $params;
    
    const ERROR_GENERIC_CODE = 500;
    
    const ERROR_GENERIC_MESSAGE = 'Error de conexión';

    public function __construct($config, $params) {
        $this->config = $config;
        $this->params = $params;
    }

    public function getByTrackingNumber($trackingNumber, $params) 
    {
        $url = $this->config['host'] . 'vis/track/pin/' . $trackingNumber . '/details';
        return $this->send($url, $params);
    }

    private function send($url, $params) {
        
        $response = array(
            'success' => false,
            'error' => array(
                'code' => self::ERROR_GENERIC_CODE,
                'message' => self::ERROR_GENERIC_MESSAGE
                )
            );
        try {
          
            $this->client = new Client(null, array(
                'adapter' => 'Zend\Http\Client\Adapter\Curl',
                'curloptions' => array(
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_TIMEOUT => 120,
                ),
            )); 
            $this->client->setUri($url);
            $this->client->setMethod('GET');
            $this->client->setAuth($this->config['CustomerNumber'], $this->config['ContractId'])
                    ->setEncType('text/xml');
            
            $this->client->setHeaders(
                    array(
                        'Content-Type' => 'application/vnd.cpc.shipment-v7+xml',                    
                        'Accept' => 'application/vnd.cpc.shipment-v7+xml',
                    )
                );
            $responseHttp = $this->client->send();
            /*$objec = new Client();
            $r = $objec->send();
            $r->isOk();
            $r->getReasonPhrase();$r->getStatusCode()*/
         
            if($responseHttp->isOK()) {
                $responseBody = $this->parseXml($responseHttp->getBody());
                $response = array(
                    'success' => true,
                    'data' => $responseBody
                ); 
            } else {
                $response = array(
                    'success' => false,
                    'error' => array(
                        'code' => $responseHttp->getStatusCode(),
                        'message' => $responseHttp->getReasonPhrase()
                        )
                    ); 
            }
            
        } catch (\Exception $e) {
           $response = array(
            'success' => false,
            'error' => array(
                'code' => $e->getCode(),
                'message' => $e->getMessage()
                )
            ); 
            //echo $e->getTraceAsString();
        }
        return $response;
    }
    
    protected function parseXml($xmlString)
    {
        $xml = simplexml_load_string($xmlString);
        return json_decode(json_encode($xml), TRUE);
    }

}
